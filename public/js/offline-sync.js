/**
 * KopiLink - Offline Form Queue & Auto-Sync Engine
 * Menyimpan formulir panen petani ke IndexedDB saat offline dan
 * menyinkronkannya secara otomatis ke database saat kembali online.
 */

const KopiLinkOffline = (() => {
    const DB_NAME = 'kopilink_offline_db';
    const DB_VERSION = 1;
    const STORE_NAME = 'offline_forms';

    let dbPromise = null;
    let isSyncing = false;

    function openDB() {
        if (dbPromise) return dbPromise;
        dbPromise = new Promise((resolve, reject) => {
            const request = indexedDB.open(DB_NAME, DB_VERSION);
            request.onupgradeneeded = (e) => {
                const db = e.target.result;
                if (!db.objectStoreNames.contains(STORE_NAME)) {
                    db.createObjectStore(STORE_NAME, { keyPath: 'id', autoIncrement: true });
                }
            };
            request.onsuccess = () => resolve(request.result);
            request.onerror = () => reject(request.error);
        });
        return dbPromise;
    }

    async function saveForm(item) {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const tx = db.transaction(STORE_NAME, 'readwrite');
            const store = tx.objectStore(STORE_NAME);
            const req = store.add(item);
            req.onsuccess = () => resolve(req.result);
            req.onerror = () => reject(req.error);
        });
    }

    async function getAllForms() {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const tx = db.transaction(STORE_NAME, 'readonly');
            const store = tx.objectStore(STORE_NAME);
            const req = store.getAll();
            req.onsuccess = () => resolve(req.result || []);
            req.onerror = () => reject(req.error);
        });
    }

    async function deleteForm(id) {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const tx = db.transaction(STORE_NAME, 'readwrite');
            const store = tx.objectStore(STORE_NAME);
            const req = store.delete(id);
            req.onsuccess = () => resolve();
            req.onerror = () => reject(req.error);
        });
    }

    function fileToBase64(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = () => resolve({
                name: file.name,
                type: file.type,
                base64: reader.result
            });
            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    }

    function base64ToFile(base64Data, filename, mimeType) {
        const arr = base64Data.split(',');
        const bstr = atob(arr[1]);
        let n = bstr.length;
        const u8arr = new Uint8Array(n);
        while (n--) {
            u8arr[n] = bstr.charCodeAt(n);
        }
        return new File([u8arr], filename, { type: mimeType });
    }

    // Perbarui Tampilan Indikator Sinyal & Antrean di Topbar
    async function updateStatusUI() {
        const container = document.getElementById('offline-sync-indicator');
        if (!container) return;

        const forms = await getAllForms().catch(() => []);
        const count = forms.length;
        const isOnline = navigator.onLine;

        let html = '';

        if (!isOnline) {
            html += `<span class="badge badge-warning py-1 px-2 text-dark font-weight-bold mr-2" style="background-color: #DA9F5B;">
                <i class="fa fa-wifi-slash mr-1"></i> Mode Offline
            </span>`;
        }

        if (count > 0) {
            html += `<button type="button" onclick="KopiLinkOffline.syncNow()" class="btn btn-sm btn-dark text-warning border-warning font-weight-bold py-1 px-2 shadow-sm animate-pulse" title="Klik untuk sinkronkan data sekarang">
                <i class="fa fa-cloud-upload-alt mr-1"></i> ${count} Data Tertunda
            </button>`;
        } else if (isOnline) {
            html += `<span class="badge badge-light text-muted small py-1 px-2 d-none d-lg-inline">
                <i class="fa fa-circle text-success mr-1" style="font-size: 8px;"></i> Terhubung
            </span>`;
        }

        container.innerHTML = html;
    }

    // Tangkap Submit Formulir Offline
    function attachFormInterceptors() {
        document.querySelectorAll('form[data-offline-sync="true"]').forEach((form) => {
            if (form.dataset.offlineAttached) return;
            form.dataset.offlineAttached = 'true';

            form.addEventListener('submit', async (e) => {
                if (navigator.onLine) {
                    // Jika online, biarkan submit normal
                    return;
                }

                e.preventDefault();

                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin mr-1"></i> Menyimpan ke memori HP...';
                }

                try {
                    const fields = {};
                    const files = [];

                    // Baca semua elemen formulir
                    const elements = Array.from(form.elements);
                    for (const el of elements) {
                        if (!el.name || el.disabled) continue;

                        if (el.type === 'file') {
                            if (el.files && el.files.length > 0) {
                                for (let i = 0; i < el.files.length; i++) {
                                    const fileData = await fileToBase64(el.files[i]);
                                    files.push({
                                        field: el.name,
                                        filename: fileData.name,
                                        type: fileData.type,
                                        base64: fileData.base64
                                    });
                                }
                            }
                        } else if (el.type === 'checkbox' || el.type === 'radio') {
                            if (el.checked) fields[el.name] = el.value;
                        } else {
                            fields[el.name] = el.value;
                        }
                    }

                    const formTitle = form.dataset.formTitle || document.title.split('-')[0].trim();
                    const redirectUrl = form.dataset.redirectUrl || window.location.href;

                    await saveForm({
                        url: form.action,
                        fields: fields,
                        files: files,
                        title: formTitle,
                        redirectUrl: redirectUrl,
                        createdAt: new Date().toISOString()
                    });

                    // Daftarkan Background Sync API jika didukung browser
                    if ('serviceWorker' in navigator && 'SyncManager' in window) {
                        try {
                            const reg = await navigator.serviceWorker.ready;
                            await reg.sync.register('kopilink-sync-forms');
                        } catch (err) {
                            console.log('Background Sync not supported/permitted:', err);
                        }
                    }

                    await updateStatusUI();

                    // Tampilkan notifikasi ramah offline
                    showOfflineAlert(formTitle, redirectUrl);

                } catch (err) {
                    alert('Gagal menyimpan data offline: ' + err.message);
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fa fa-save mr-1"></i> Coba Lagi';
                    }
                }
            });
        });
    }

    function showOfflineAlert(title, redirectUrl) {
        const modalHtml = `
            <div id="offlineModal" class="modal fade show" style="display: block; background: rgba(0,0,0,0.6);" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg text-center" style="background-color: #33211D; color: #fff; border-radius: 12px; border: 1px solid #DA9F5B !important;">
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <span class="d-inline-flex align-items-center justify-content-center" style="width: 64px; height: 64px; border-radius: 50%; background: rgba(218, 159, 91, 0.2); color: #DA9F5B; font-size: 28px;">
                                    <i class="fa fa-save"></i>
                                </span>
                            </div>
                            <h5 class="text-white font-weight-bold mb-2">Tersimpan di Memori HP (Mode Offline)</h5>
                            <p class="text-white-50 small mb-4">
                                Data <strong>${title}</strong> dan foto Anda telah berhasil disimpan di perangkat ini. 
                                Sistem akan otomatis mengunggah data ini ke server begitu HP Anda mendapatkan sinyal internet!
                            </p>
                            <button type="button" class="btn btn-primary font-weight-bold px-4 py-2" style="color: #33211D !important;" onclick="window.location.href='${redirectUrl}'">
                                <i class="fa fa-check mr-1"></i> Mengerti & Lanjutkan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHtml);
    }

    // Eksekusi Sinkronisasi Data Tertunda
    async function syncNow() {
        if (!navigator.onLine) {
            alert('Perangkat Anda masih dalam kondisi offline. Sinkronisasi akan otomatis berjalan saat sinyal internet kembali.');
            return;
        }

        if (isSyncing) return;
        isSyncing = true;

        const forms = await getAllForms().catch(() => []);
        if (forms.length === 0) {
            isSyncing = false;
            await updateStatusUI();
            return;
        }

        // Ambil token CSRF terbaru dari server
        let freshCsrf = null;
        try {
            const tokenRes = await fetch('/csrf-token', { headers: { 'Accept': 'application/json' } });
            if (tokenRes.ok) {
                const data = await tokenRes.json();
                freshCsrf = data.token;
            }
        } catch (e) {
            // Gunakan token yang tersimpan di meta jika fetch gagal
            freshCsrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        }

        let syncedCount = 0;

        for (const item of forms) {
            try {
                const formData = new FormData();

                // Masukkan fields
                for (const [key, val] of Object.entries(item.fields)) {
                    if (key === '_token' && freshCsrf) {
                        formData.append('_token', freshCsrf);
                    } else {
                        formData.append(key, val);
                    }
                }

                if (freshCsrf && !formData.has('_token')) {
                    formData.append('_token', freshCsrf);
                }

                // Masukkan file blobs
                if (item.files && item.files.length > 0) {
                    item.files.forEach((f) => {
                        const fileObj = base64ToFile(f.base64, f.filename, f.type);
                        formData.append(f.field, fileObj);
                    });
                }

                const res = await fetch(item.url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        ...(freshCsrf ? { 'X-CSRF-TOKEN': freshCsrf } : {})
                    }
                });

                if (res.ok || res.redirected) {
                    await deleteForm(item.id);
                    syncedCount++;
                } else if (res.status === 419 || res.status === 401) {
                    console.warn('Sesi kedaluwarsa saat sinkronisasi offline item #', item.id);
                    break;
                }
            } catch (err) {
                console.error('Gagal menyinkronkan item offline #', item.id, err);
                break;
            }
        }

        isSyncing = false;
        await updateStatusUI();

        if (syncedCount > 0) {
            showToastSuccess(`✅ Berhasil menyinkronkan ${syncedCount} data panen offline ke server.`);
            // Jika berada di halaman daftar data, muat ulang agar data baru langsung tampak
            setTimeout(() => {
                if (window.location.pathname.includes('/petani/')) {
                    window.location.reload();
                }
            }, 1200);
        }
    }

    function showToastSuccess(message) {
        const toast = document.createElement('div');
        toast.className = 'fixed-bottom p-3 d-flex justify-content-center';
        toast.style.zIndex = '99999';
        toast.innerHTML = `
            <div class="alert alert-success shadow-lg border-0 py-2 px-3 font-weight-bold" style="background-color: #DA9F5B; color: #111; border-radius: 8px;">
                ${message}
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    }

    // Inisialisasi event listener
    function init() {
        attachFormInterceptors();
        updateStatusUI();

        window.addEventListener('online', () => {
            updateStatusUI();
            setTimeout(syncNow, 1000);
        });

        window.addEventListener('offline', () => {
            updateStatusUI();
        });

        // Pesan dari Service Worker Background Sync
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.addEventListener('message', (event) => {
                if (event.data && event.data.type === 'SYNC_NOW') {
                    syncNow();
                }
            });
        }

        // Cek antrean saat halaman baru dimuat
        if (navigator.onLine) {
            setTimeout(syncNow, 2000);
            prewarmPetaniCache();
        }
    }

    // Pra-simpan rute dashboard petani ke cache agar profil, panen, dan kebun bisa dibuka offline
    async function prewarmPetaniCache() {
        if (!navigator.onLine || !window.location.pathname.startsWith('/petani')) return;
        if (!('caches' in window)) return;

        const routes = [
            '/petani/dashboard',
            '/petani/profil',
            '/petani/ceri',
            '/petani/ceri/create',
            '/petani/produksi',
            '/petani/produksi/create',
            '/petani/lahan',
            '/petani/lahan/create'
        ];

        try {
            const cache = await caches.open('kopilink-pwa-v6');
            for (const r of routes) {
                const match = await cache.match(r);
                if (!match) {
                    fetch(r).then((res) => {
                        if (res && res.status === 200) cache.put(r, res);
                    }).catch(() => {});
                }
            }
        } catch (e) {
            // Abaikan jika browser membatasi cache
        }
    }

    return {
        init,
        syncNow,
        updateStatusUI
    };
})();

document.addEventListener('DOMContentLoaded', () => {
    KopiLinkOffline.init();
});

