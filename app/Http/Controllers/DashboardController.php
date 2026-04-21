<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tamu;        // Pastikan model di-import
use Illuminate\Support\Facades\DB;  // Pastikan DB di-import
use Carbon\Carbon;            // Pastikan Carbon di-import

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama.
     * Method ini juga membaca 'period' dari URL untuk bookmark/refresh.
     */
    public function index(Request $request)
    {
        // === 1. LOGIKA UNTUK KPI CARDS ===
        $totalTamu = Tamu::count();
        $tamuHariIni = Tamu::whereDate('created_at', now()->toDateString())->count();
        $tamuBulanIni = Tamu::whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year)
                            ->count();

                            
        // === 2. LOGIKA UNTUK DATA CHART AWAL (SAAT HALAMAN DIMUAT) ===

        // Ambil 'period' dari request, jika tidak ada, default-nya 7
        $validPeriods = [7, 14, 30];
        $period = (int) $request->query('period', $validPeriods[0]); 
        if (!in_array($period, $validPeriods)) {
            $period = $validPeriods[0];
        }

        // Tentukan rentang tanggal
        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays($period - 1)->startOfDay();

        // Query data tamu (efisien, 1 query)
        $tamuData = Tamu::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()->keyBy(fn($item) => Carbon::parse($item->date)->format('Y-m-d'));

        // Siapkan data untuk chart (isi hari yang kosong dengan 0)
        $labels = [];
        $counts = [];
        $currentDate = $startDate->clone();
        while ($currentDate->lte($endDate)) {
            $dateString = $currentDate->format('Y-m-d');
            $labels[] = $currentDate->format('d M'); 
            $counts[] = $tamuData->get($dateString)->count ?? 0;
            $currentDate->addDay();
        }

        // === 3. KIRIM SEMUA DATA KE VIEW ===
        return view('admin.dashboard', [
            'totalTamu'     => $totalTamu,
            'tamuHariIni'   => $tamuHariIni,
            'tamuBulanIni'  => $tamuBulanIni,
            'labels'        => $labels,
            'counts'        => $counts,
            'period'        => $period, // Kirim periode yang sedang aktif
        ]);
    }


    /**
     * [ENDPOINT API] Mengambil data chart untuk AJAX request.
     * Hanya mengembalikan JSON.
     */
    public function getChartData(Request $request)
    {
        // 1. Validasi periode
        $validPeriods = [7, 14, 30];
        $period = (int) $request->query('period', 7);
        if (!in_array($period, $validPeriods)) {
            $period = 7;
        }

        // 2. Tentukan rentang tanggal
        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays($period - 1)->startOfDay();

        // 3. Query data
        $tamuData = Tamu::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()->keyBy(fn($item) => Carbon::parse($item->date)->format('Y-m-d'));

        // 4. Siapkan data
        $labels = [];
        $counts = [];
        $currentDate = $startDate->clone();
        while ($currentDate->lte($endDate)) {
            $dateString = $currentDate->format('Y-m-d');
            $labels[] = $currentDate->format('d M'); 
            $counts[] = $tamuData->get($dateString)->count ?? 0;
            $currentDate->addDay();
        }

        // 5. Kembalikan data sebagai JSON
        return response()->json([
            'labels' => $labels,
            'counts' => $counts,
        ]);
    }
}