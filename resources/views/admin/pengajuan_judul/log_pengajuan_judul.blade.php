@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Banner Info Koneksi --}}
    <div class="rounded-lg border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/50 p-4">
        <div class="flex">
            <div class="flex-shrink-0 text-2xl">📡</div>
            <div class="ml-3">
                <h6 class="text-sm font-bold text-green-800 dark:text-green-400">Koneksi API Sistem Pengajuan Judul Terdeteksi!</h6>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Setiap kali mahasiswa di aplikasi <strong>sistem-pengajuan-judul</strong> mengirim pengajuan judul skripsi, data akan langsung dilempar kesini secara otomatis lewat jaringan lokal (Wi-Fi) tanpa perlu input manual.</p>
            </div>
        </div>
    </div>

    {{-- Card Utama --}}
    <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm transition-colors duration-200">
        
        {{-- Header Card --}}
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 px-6 py-4">
            <h5 class="flex items-center gap-2 text-lg font-semibold text-gray-900 dark:text-white">
                🎓 Pusat Data - Log Pengajuan Judul Skripsi
            </h5>
            <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/50 px-3 py-1 text-xs font-medium text-green-800 dark:text-green-400">
                <span class="mr-1.5 h-2 w-2 rounded-full bg-green-400 dark:bg-green-500"></span>
                Status: Real-Time Terhubung
            </span>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">No</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">NIM</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Nama Mahasiswa</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Prodi / Fakultas</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Judul Skripsi</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Waktu Mendarat di Pusat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @forelse($logs as $index => $log)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                            <td class="px-4 py-3 text-center text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>

                            <td class="px-4 py-3 text-center text-sm font-bold text-blue-600 dark:text-blue-400">{{ $log->nim }}</td>

                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $log->nama_mahasiswa }}</td>

                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                {{ $log->prodi ?? '-' }}
                                <br>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $log->fakultas ?? '-' }}</span>
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white max-w-xs">{{ $log->judul }}</td>

                            <td class="px-4 py-3 text-center">
                                @if(strtolower($log->status) == 'waiting' || strtolower($log->status) == 'menunggu')
                                    <span class="inline-flex items-center rounded-full bg-amber-100 dark:bg-amber-900/50 px-2.5 py-1 text-xs font-medium text-amber-800 dark:text-amber-400">
                                        {{ $log->status }}
                                    </span>
                                @elseif(strtolower($log->status) == 'rejected' || strtolower($log->status) == 'ditolak')
                                    <span class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/50 px-2.5 py-1 text-xs font-medium text-red-800 dark:text-red-400">
                                        {{ $log->status }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/50 px-2.5 py-1 text-xs font-medium text-green-800 dark:text-green-400">
                                        {{ $log->status }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-center text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s ~ d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center">
                                <div class="text-4xl">📭</div>
                                <p class="mt-3 text-sm font-medium text-gray-700 dark:text-gray-300">Belum ada data pengajuan judul yang disetor dari sistem pengajuan judul!</p>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Silakan lakukan simulasi pengajuan judul dulu di sistem pengajuan judul.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection