<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogPresensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        $query = LogPresensi::query()->with('mahasiswa');

        // Filter berdasarkan NIM
        if ($request->filled('nim')) {
            $query->where('nim_mahasiswa', 'like', '%' . $request->nim . '%');
        }

        // Filter berdasarkan Kelas
        if ($request->filled('kelas')) {
            $query->where('kode_kelas', 'like', '%' . $request->kelas . '%');
        }

        // Filter berdasarkan Status
        if ($request->filled('status')) {
            $query->where('status_kehadiran', $request->status);
        }

        // Filter berdasarkan Tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('waktu', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('waktu', '<=', $request->tanggal_sampai);
        }

        $logs = $query->orderBy('waktu', 'desc')->paginate(20);

        // Statistik
        $totalPresensi = LogPresensi::count();
        $totalHadir = LogPresensi::where('status_kehadiran', 'hadir')->count();
        $totalTerlambat = LogPresensi::where('status_kehadiran', 'terlambat')->count();
        $totalAlpha = LogPresensi::where('status_kehadiran', 'alpha')->count();

        // Presensi per hari (7 hari terakhir)
        $presensiPerHari = LogPresensi::select(
            DB::raw('DATE(waktu) as tanggal'),
            DB::raw('COUNT(*) as total')
        )
            ->where('waktu', '>=', now()->subDays(7))
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('admin.presensi.index', compact(
            'logs',
            'totalPresensi',
            'totalHadir',
            'totalTerlambat',
            'totalAlpha',
            'presensiPerHari'
        ));
    }

    public function show($id)
    {
        $log = LogPresensi::with('mahasiswa')->findOrFail($id);
        return view('admin.presensi.show', compact('log'));
    }

    public function export(Request $request)
    {
        $query = LogPresensi::query()->with('mahasiswa');

        // Apply same filters as index
        if ($request->filled('nim')) {
            $query->where('nim_mahasiswa', 'like', '%' . $request->nim . '%');
        }
        if ($request->filled('kelas')) {
            $query->where('kode_kelas', 'like', '%' . $request->kelas . '%');
        }
        if ($request->filled('status')) {
            $query->where('status_kehadiran', $request->status);
        }
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('waktu', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('waktu', '<=', $request->tanggal_sampai);
        }

        $logs = $query->orderBy('waktu', 'desc')->get();

        $filename = 'log_presensi_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, ['No', 'NIM', 'Nama Mahasiswa', 'Kode Kelas', 'Mata Kuliah', 'Status', 'Waktu', 'Sistem Asal']);

            // Data
            $no = 1;
            foreach ($logs as $log) {
                fputcsv($file, [
                    $no++,
                    $log->nim_mahasiswa,
                    $log->nama_mahasiswa ?? ($log->mahasiswa->nama ?? '-'),
                    $log->kode_kelas,
                    $log->nama_mata_kuliah,
                    $log->status_label,
                    $log->waktu->format('d/m/Y H:i:s'),
                    $log->sistem_asal,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
