<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Tamu;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage; 

class TamuController extends Controller
{
    public function index()
    {
        return view('form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'instansi' => 'nullable',
            'nomor' => 'nullable',
            'keperluan' => 'required',
            'foto' => 'required'
        ]);

        if ($request->foto) {
            $image = str_replace('data:image/png;base64,', '', $request->foto);
            $image = str_replace(' ', '+', $image);
            $imageName = 'tamu_' . time() . '.png';

            Storage::disk('public')->put("foto_tamu/{$imageName}", base64_decode($image));
        }

    Tamu::create([
        'nama' => $request->nama,
        'instansi' => $request->instansi,
        'nomor' => $request->nomor,
        'keperluan' => $request->keperluan,
        'foto' => 'foto_tamu/' . $imageName,
    ]);

        return redirect()->route('tamu.form')->with('success', 'Data berhasil dikirim!');
    }

    public function indexAdmin(Request $request)
    {
        $query = Tamu::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                ->orWhere('nomor', 'like', "%$search%")
                ->orWhere('instansi', 'like', "%$search%")
                ->orWhere('keperluan', 'like', "%$search%");
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        if ($request->filled('period')) {
            $validPeriods = [7, 14, 30];
            $period = (int) $request->period;
            if (in_array($period, $validPeriods, true)) {
                $startDate = Carbon::now()->subDays($period - 1)->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        }

        $tamus = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.daftar_tamu', compact('tamus'));
    }



    public function edit($id)
    {
        $tamu = Tamu::findOrFail($id);
        return view('admin.tamu.edit', compact('tamu'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'instansi' => 'nullable',
            'nomor' => 'nullable',
            'keperluan' => 'required',
        ]);

        $tamu = Tamu::findOrFail($id);
        $tamu->update($request->all());

        return redirect()->route('tamu.index')->with('success', 'Data tamu berhasil diupdate.');
    }

    public function markSelesai($id)
    {
        $tamu = Tamu::findOrFail($id);
        $tamu->update([
            'selesai' => now()
        ]);

        return redirect()->route('tamu.index')->with('success', 'Status tamu berhasil ditandai sebagai selesai.');
    }

    public function markNomorDiklik($id)
    {
        $tamu = Tamu::findOrFail($id);
        $tamu->update([
            'nomor_diklik' => true
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $tamu = Tamu::findOrFail($id);
        $tamu->delete();

        return redirect()->route('tamu.index')->with('success', 'Data tamu berhasil dihapus.');
    }

    public function exportPDF(Request $request)
    {
        $query = Tamu::query();

        // Sinkron dengan filter pada indexAdmin (search, tanggal)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                ->orWhere('nomor', 'like', "%$search%")
                ->orWhere('instansi', 'like', "%$search%")
                ->orWhere('keperluan', 'like', "%$search%");
            });
        }
        
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        if ($request->filled('period')) {
            $validPeriods = [7, 14, 30];
            $period = (int) $request->period;
            if (in_array($period, $validPeriods, true)) {
                $startDate = Carbon::now()->subDays($period - 1)->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        }

        $daftarTamu = $query->latest()->get();

        $pdf = Pdf::loadView('admin.tamu_pdf', compact('daftarTamu'));
        $fileName = 'daftar-tamu-' . date('Y-m-d') . '.pdf';

        return $pdf->download($fileName);
    }
}