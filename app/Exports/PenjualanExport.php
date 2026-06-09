<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PenjualanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Transaksi::with(['barang', 'user'])
            ->get()
            ->map(function ($t) {
                return [
                    'ID Transaksi' => $t->id,
                    'Nama Barang' => $t->barang->nama_barang,
                    'Penjual' => $t->barang->user->name,
                    'Pembeli' => $t->user->name,
                    'Jumlah' => $t->jumlah,
                    'Total Harga' => $t->total_harga,
                    'Tanggal' => $t->created_at->format('Y-m-d H:i'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID Transaksi',
            'Nama Barang',
            'Penjual',
            'Pembeli',
            'Jumlah',
            'Total Harga',
            'Tanggal',
        ];
    }
}