@extends('layouts.app') {{-- Mewarisi template utama , sesuaikan dengan nama file di folder layouts Angga jika berbeda --}}

@section('content')
<div class="container-fluid px-4 py-4">
    
    <div class="alert alert-success d-flex align-items-center border-0 shadow-sm mb-4" role="alert" style="background-color: #e8f5e9;">
        <div class="fs-3 me-3">📡</div>
        <div>
            <h6 class="alert-heading mb-1 fw-bold text-success">Koneksi API Sistem Perpustakaan Cabang Terdeteksi!</h6>
            <p class="mb-0 small text-muted">Setiap kali admin di aplikasi <strong>sistem-perpustakaan</strong> menekan tombol catat transaksi, data akan langsung dilempar kesini secara otomatis lewat jaringan lokal (Wi-Fi) gais tanpa perlu input manual.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 text-white fw-bold">🖥️ Pusat Data - Log Sinkronisasi Masuk</h5>
            <span class="badge bg-success px-3 py-2">Status: Real-Time Terhubung</span>
        </div>
        <div class="card-body">
            
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light text-center small fw-bold">
                        <tr>
                            <th width="5%">No</th>
                            <th>Asal Sumber Data</th> <th>NIM / NIP Peminjam</th>
                            <th>Kode Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Status Buku</th>
                            <th>Waktu Mendarat di Pusat</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        @forelse($logs as $index => $log)
                            <tr>
                                <td class="text-center text-muted">{{ $index + 1 }}</td>
                                
                                <td class="text-center">
                                    <span class="badge bg-info text-dark fw-bold px-2.5 py-1.5" style="font-size: 11px;">
                                        🔌 Cabang: sistem-perpustakaan
                                    </span>
                                </td>

                                <td class="fw-bold text-primary text-center">{{ $log->nim_peminjam }}</td>
                                <td class="text-center"><span class="badge bg-secondary px-2.5">{{ $log->kode_buku }}</span></td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($log->tanggal_pinjam)->format('d-m-Y') }}</td>
                                <td class="text-center">
                                    <span class="badge bg-warning text-dark px-2.5">{{ $log->status }}</span>
                                </td>
                                <td class="text-center text-muted fw-medium">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s ~ d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-danger fw-bold py-5">
                                    <div class="fs-2">📭</div>
                                    <div class="mt-2">Belum ada data transaksi yang disetor dari aplikasi perpustakaan gais!</div>
                                    <small class="text-muted fw-normal">Silakan lakukan simulasi peminjaman buku dulu di laptop kamu gais.</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection