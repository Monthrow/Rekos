<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransaksiExport;

class ReportController extends Controller
{
    // ===================== PENJUAL =====================

    public function penjualPdf()
    {
        $user = Auth::user();
        $transaksis = Transaksi::with(['barang', 'user'])
            ->whereHas('barang', function ($q) use ($user) {
                $q->where('id_user', $user->id_user);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $total_pendapatan = $transaksis->where('status', 'Selesai')->sum('total_harga');

        $pdf = Pdf::loadView('report.penjual_pdf', compact('transaksis', 'total_pendapatan', 'user'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-penjualan-' . now()->format('Y-m-d') . '.pdf');
    }

    public function penjualExcel()
    {
        return Excel::download(
            new TransaksiExport('penjual', Auth::id()),
            'laporan-penjualan-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    // ===================== ADMIN =====================

    public function adminPdf()
    {
        $transaksis = Transaksi::with(['barang', 'barang.penjual', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $total_pendapatan = $transaksis->where('status', 'Selesai')->sum('total_harga');

        $pdf = Pdf::loadView('report.admin_pdf', compact('transaksis', 'total_pendapatan'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-transaksi-admin-' . now()->format('Y-m-d') . '.pdf');
    }

    public function adminExcel()
    {
        return Excel::download(
            new TransaksiExport('admin'),
            'laporan-transaksi-admin-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}