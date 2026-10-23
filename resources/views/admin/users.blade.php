@extends('layouts.dashboard')

@section('title', 'Manajemen User - Super Admin KopiLink')
@section('page_title', 'Manajemen Pengguna Sistem')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="font-weight-bold text-dark mb-1">Daftar Akun Pengguna</h4>
            <p class="text-muted small mb-0">Kelola akses akun Petani, Coffee Shop mitra, dan Administrator sistem.</p>
        </div>
        <div>
            <button type="button" class="btn btn-koppee" data-toggle="modal" data-target="#modalAddUser">
                <i class="fa fa-user-plus mr-1"></i> Tambah Akun Baru
            </button>
        </div>
    </div>
</div>

<!-- Nav Tabs User Categories -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 8px;">
    <div class="card-header bg-white border-bottom p-0">
        <ul class="nav nav-tabs border-0 px-3 pt-2" id="userManagementTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active font-weight-bold" id="tab-petani-btn" data-toggle="tab" href="#tab-petani" role="tab">
                    <i class="fa fa-users text-primary mr-1"></i> Petani Kopi ({{ $petanis->count() }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" id="tab-coffeeshop-btn" data-toggle="tab" href="#tab-coffeeshop" role="tab">
                    <i class="fa fa-store text-success mr-1"></i> Coffee Shop & Roastery ({{ $coffeeShops->count() }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" id="tab-admin-btn" data-toggle="tab" href="#tab-admin" role="tab">
                    <i class="fa fa-user-shield text-warning mr-1"></i> Super Admin ({{ $admins->count() }})
                </a>
            </li>
        </ul>
    </div>

    <div class="card-body p-0">
        <div class="tab-content" id="userManagementContent">
            
            <!-- 1. TAB PETANI -->
            <div class="tab-pane fade show active p-3" id="tab-petani" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Petani</th>
                                <th>Username</th>
                                <th>WhatsApp</th>
                                <th>Kelompok Tani</th>
                                <th>Status Verifikasi</th>
                                <th>Lahan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($petanis as $idx => $p)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td>
                                        <div class="font-weight-bold text-dark">{{ $p->nama }}</div>
                                        <small class="text-muted">{{ $p->alamat ?? 'Alamat belum diatur' }}</small>
                                    </td>
                                    <td><code>{{ $p->username }}</code></td>
                                    <td>+{{ $p->kontak }}</td>
                                    <td><span class="badge badge-light border">{{ $p->kelompok_tani }}</span></td>
                                    <td>
                                        @if($p->is_verified)
                                            <span class="badge badge-success px-2 py-1"><i class="fa fa-check-circle mr-1"></i>Terverifikasi</span>
                                        @else
                                            <span class="badge badge-warning px-2 py-1" style="background-color: #DA9F5B; color: #111;">Pending OTP</span>
                                        @endif
                                    </td>
                                    <td>{{ $p->lahan_count }} Kebun</td>
                                    <td class="text-center">
                                        @if(!$p->is_verified)
                                            <form action="{{ route('admin.users.verify', $p->id_petani) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success mr-1" title="Verifikasi Akun Manual">
                                                    <i class="fa fa-user-check"></i> Verifikasi
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <!-- Tombol Reset Password -->
                                        <button type="button" class="btn btn-sm btn-outline-secondary mr-1" data-toggle="modal" data-target="#modalResetPass" data-type="petani" data-id="{{ $p->id_petani }}" data-name="{{ $p->nama }}">
                                            <i class="fa fa-key"></i>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('admin.users.destroy', ['type' => 'petani', 'id' => $p->id_petani]) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus petani {{ $p->nama }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus User">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">Belum ada data petani.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. TAB COFFEE SHOP -->
            <div class="tab-pane fade p-3" id="tab-coffeeshop" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Coffee Shop / Roastery</th>
                                <th>Username</th>
                                <th>Kemasan Diterbitkan</th>
                                <th>Tanggal Bergabung</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($coffeeShops as $idx => $cs)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td class="font-weight-bold text-dark">{{ $cs->nama_toko }}</td>
                                    <td><code>{{ $cs->username }}</code></td>
                                    <td><span class="badge badge-info">{{ $cs->kemasan_count }} Produk QR</span></td>
                                    <td>{{ $cs->created_at->format('d M Y') }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-secondary mr-1" data-toggle="modal" data-target="#modalResetPass" data-type="coffeeshop" data-id="{{ $cs->id_coffee_shop }}" data-name="{{ $cs->nama_toko }}">
                                            <i class="fa fa-key"></i> Reset Sandi
                                        </button>
                                        <form action="{{ route('admin.users.destroy', ['type' => 'coffeeshop', 'id' => $cs->id_coffee_shop]) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus toko {{ $cs->nama_toko }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada akun coffee shop.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 3. TAB SUPER ADMIN -->
            <div class="tab-pane fade p-3" id="tab-admin" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Lengkap</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admins as $idx => $adm)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td class="font-weight-bold text-dark">{{ $adm->name }}</td>
                                    <td><code>{{ $adm->username }}</code></td>
                                    <td>{{ $adm->email }}</td>
                                    <td><span class="badge badge-warning" style="background-color: #DA9F5B; color: #111;">Super Admin</span></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-secondary mr-1" data-toggle="modal" data-target="#modalResetPass" data-type="admin" data-id="{{ $adm->id }}" data-name="{{ $adm->name }}">
                                            <i class="fa fa-key"></i> Reset Sandi
                                        </button>
                                        @if($adm->id !== auth('web')->id())
                                            <form action="{{ route('admin.users.destroy', ['type' => 'admin', 'id' => $adm->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus admin {{ $adm->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge badge-light border text-muted">Akun Anda</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Tambah Akun Baru -->
<div class="modal fade" id="modalAddUser" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title font-weight-bold text-white"><i class="fa fa-user-plus mr-2 text-warning"></i> Tambah Akun Pengguna</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Peran Pengguna</label>
                        <select name="role_type" id="role_type" class="form-control" required>
                            <option value="coffeeshop">Coffee Shop / Mitra Roastery</option>
                            <option value="admin">Super Administrator</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold" id="label-nama">Nama Toko / Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" placeholder="Contoh: Kopi Senja Roastery" required>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="username_unik" required>
                    </div>

                    <div class="form-group" id="group-email" style="display: none;">
                        <label class="font-weight-bold">Email (Opsional untuk Admin)</label>
                        <input type="email" name="email" class="form-control" placeholder="admin@domain.id">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Password Akun</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-koppee font-weight-bold">Simpan Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reset Password -->
<div class="modal fade" id="modalResetPass" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h6 class="modal-title font-weight-bold text-white"><i class="fa fa-key mr-2 text-warning"></i> Reset Password</h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="formResetPass" method="POST">
                @csrf
                <div class="modal-body p-3">
                    <p class="small text-muted mb-2">Ganti password untuk: <strong id="resetTargetName" class="text-dark"></strong></p>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold small">Password Baru</label>
                        <input type="password" name="new_password" class="form-control form-control-sm" placeholder="Password baru" required minlength="6">
                    </div>
                </div>
                <div class="modal-footer bg-light p-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-koppee font-weight-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Dynamic email input in modal based on role
        const roleSelect = document.getElementById('role_type');
        const groupEmail = document.getElementById('group-email');
        if (roleSelect && groupEmail) {
            roleSelect.addEventListener('change', function () {
                groupEmail.style.display = this.value === 'admin' ? 'block' : 'none';
            });
        }

        // Modal reset password dynamic action
        $('#modalResetPass').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            const type = button.data('type');
            const id = button.data('id');
            const name = button.data('name');

            const modal = $(this);
            modal.find('#resetTargetName').text(name);
            modal.find('#formResetPass').attr('action', '/admin/users/reset-password/' + type + '/' + id);
        });
    });
</script>
@endpush
