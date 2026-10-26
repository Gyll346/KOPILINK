@extends('layouts.dashboard')

@section('title', 'Kotak Masuk Pesan Pengunjung - Super Admin')
@section('page_title', 'Kotak Masuk Pesan Pengunjung')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
    <div>
        <h4 class="font-weight-bold mb-1">Pesan Pengunjung & Kemitraan</h4>
        <p class="text-muted mb-0">Kelola dan baca pesan, penawaran kemitraan, serta pertanyaan yang dikirimkan melalui formulir Kontak Kami.</p>
    </div>
    <div class="mt-2 mt-md-0">
        @if($unreadCount > 0)
            <span class="badge badge-warning text-dark px-3 py-2 font-weight-bold" style="background-color: #ffd166; font-size: 13px;">
                <i class="fa fa-envelope mr-1"></i> {{ $unreadCount }} Pesan Baru Belum Dibaca
            </span>
        @else
            <span class="badge badge-success px-3 py-2 font-weight-bold" style="font-size: 13px;">
                <i class="fa fa-check-circle mr-1"></i> Semua Pesan Telah Dibaca
            </span>
        @endif
    </div>
</div>

<div class="card card-stat">
    <div class="card-header bg-white border-0 pt-3 px-3 pb-2 d-flex justify-content-between align-items-center flex-wrap">
        <h6 class="font-weight-bold m-0 text-dark mb-2 mb-md-0">
            <i class="fa fa-inbox text-primary mr-2"></i> Daftar Pesan ({{ $pesanList->count() }})
        </h6>
        <div style="min-width: 250px; max-width: 320px;">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-light border-right-0"><i class="fa fa-search text-muted"></i></span>
                </div>
                <input type="text" class="form-control border-left-0" id="searchPesanInput" placeholder="Cari pengirim, email, subjek..." onkeyup="filterPesanTable()">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if($pesanList->isEmpty())
            <div class="text-center py-5">
                <i class="fa fa-envelope-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada pesan yang masuk.</h5>
                <p class="text-muted small">Pesan yang dikirim pengunjung dari halaman Kontak Kami akan tampil di sini.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="pesanTable">
                    <thead class="thead-light">
                        <tr>
                            <th class="pl-4" style="width: 130px;">Status</th>
                            <th>Pengirim</th>
                            <th>Email</th>
                            <th>Subjek</th>
                            <th>Cuplikan Pesan</th>
                            <th>Diterima Pada</th>
                            <th class="text-center pr-4" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesanList as $msg)
                            <tr class="{{ !$msg->is_read ? 'table-warning font-weight-bold' : '' }}">
                                <td class="pl-4">
                                    @if(!$msg->is_read)
                                        <span class="badge badge-warning text-dark px-2 py-1" style="background-color: #ffd166; font-size: 11px;">
                                            <i class="fa fa-envelope mr-1"></i> Baru
                                        </span>
                                    @else
                                        <span class="badge badge-light border text-muted px-2 py-1" style="font-size: 11px;">
                                            <i class="fa fa-check mr-1"></i> Dibaca
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $msg->nama }}</strong>
                                </td>
                                <td>
                                    <a href="mailto:{{ $msg->email }}" class="text-primary text-decoration-none">
                                        <i class="fa fa-envelope-open mr-1 small"></i>{{ $msg->email }}
                                    </a>
                                </td>
                                <td>
                                    <span class="text-dark">{{ $msg->subjek }}</span>
                                </td>
                                <td>
                                    <span class="text-muted font-weight-normal small">
                                        {{ \Illuminate\Support\Str::limit($msg->pesan, 45) }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted font-weight-normal">
                                        {{ $msg->created_at->format('d M Y, H:i') }}
                                    </small>
                                </td>
                                <td class="text-center pr-4">
                                    <div class="btn-group" role="group">
                                        <!-- Tombol Baca Modal -->
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#modalBacaPesan{{ $msg->id_pesan }}" title="Baca Pesan Lengkap">
                                            <i class="fa fa-eye"></i>
                                        </button>

                                        <!-- Toggle Status Baca/Belum -->
                                        <form action="{{ route('admin.pesan.toggle-read', ['id_pesan' => $msg->id_pesan]) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary" title="{{ $msg->is_read ? 'Tandai Belum Dibaca' : 'Tandai Sudah Dibaca' }}">
                                                <i class="fa {{ $msg->is_read ? 'fa-envelope' : 'fa-check-double' }}"></i>
                                            </button>
                                        </form>

                                        <!-- Hapus Pesan -->
                                        <form action="{{ route('admin.pesan.destroy', ['id_pesan' => $msg->id_pesan]) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan dari {{ $msg->nama }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Pesan">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Modal Detail Pesan -->
                                    <div class="modal fade" id="modalBacaPesan{{ $msg->id_pesan }}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content border-0 shadow text-left">
                                                <div class="modal-header text-white" style="background-color: #33211D;">
                                                    <h5 class="modal-title font-weight-bold text-white">
                                                        <i class="fa fa-envelope-open mr-2 text-warning"></i> Detail Pesan Masuk
                                                    </h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-3 border-bottom pb-2">
                                                        <label class="text-muted small mb-0 d-block">PENGIRIM</label>
                                                        <h5 class="font-weight-bold text-dark mb-1">{{ $msg->nama }}</h5>
                                                        <a href="mailto:{{ $msg->email }}" class="text-primary small">
                                                            <i class="fa fa-envelope mr-1"></i>{{ $msg->email }}
                                                        </a>
                                                        <span class="text-muted small ml-2">&bull; {{ $msg->created_at->format('d F Y, H:i:s') }}</span>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="text-muted small mb-0 d-block">SUBJEK</label>
                                                        <strong class="text-dark" style="font-size: 15px;">{{ $msg->subjek }}</strong>
                                                    </div>
                                                    <div class="p-3 bg-light rounded border">
                                                        <label class="text-muted small mb-1 d-block font-weight-bold">ISI PESAN:</label>
                                                        <p class="text-dark mb-0 font-weight-normal leading-relaxed" style="white-space: pre-line;">{{ $msg->pesan }}</p>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top-0 pt-0">
                                                    <a href="mailto:{{ $msg->email }}?subject=Balasan: {{ rawurlencode($msg->subjek) }}" class="btn btn-primary text-dark font-weight-bold" style="background-color: #DA9F5B; border-color: #DA9F5B;">
                                                        <i class="fa fa-reply mr-1"></i> Balas Email
                                                    </a>
                                                    @if(!$msg->is_read)
                                                        <form action="{{ route('admin.pesan.toggle-read', ['id_pesan' => $msg->id_pesan]) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-success">
                                                                <i class="fa fa-check mr-1"></i> Tandai Sudah Dibaca
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal Detail Pesan -->
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<script>
function filterPesanTable() {
    const input = document.getElementById("searchPesanInput");
    const filter = input.value.toLowerCase();
    const table = document.getElementById("pesanTable");
    if (!table) return;
    const tr = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

    for (let i = 0; i < tr.length; i++) {
        const text = tr[i].textContent || tr[i].innerText;
        tr[i].style.display = text.toLowerCase().indexOf(filter) > -1 ? "" : "none";
    }
}
</script>
@endsection
