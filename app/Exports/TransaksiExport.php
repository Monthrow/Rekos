<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransaksiExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $role;
    protected $userId;

    public function __construct($role = 'admin', $userId = null)
    {
        $this->role = $role;
        $this->userId = $userId;
    }

    public function collection()
    {
        $query = Transaksi::with(['barang', 'barang.penjual', 'user'])
            ->orderBy('created_at', 'desc');

        if ($this->role === 'penjual') {
            $query->whereHas('barang', function ($q) {
                $q->where('id_user', $this->userId);
            });
        }

        return $query->get()->map(function ($t, $i) {
            $row = [
                'No'           => $i + 1,
                'Tanggal'      => $t->created_at->format('d/m/Y H:i'),
                'Nama Barang'  => $t->barang->nama_barang ?? '-',
                'Pembeli'      => $t->user->username ?? '-',
                'Jumlah'       => $t->jumlah_beli . ' unit',
                'Total Harga'  => 'Rp ' . number_format($t->total_harga, 0, ',', '.'),
                'Status'       => $t->status,
            ];

            if ($this->role === 'admin') {
                $row['Penjual'] = $t->barang->penjual->username ?? '-';
            }

            return $row;
        });
    }

    public function headings(): array
    {
        $base = ['No', 'Tanggal', 'Nama Barang', 'Pembeli', 'Jumlah', 'Total Harga', 'Status'];
        if ($this->role === 'admin') {
            $base[] = 'Penjual';
        }
        return $base;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF2563EB']],
            ],
        ];
    }

    public function title(): string
    {
        return $this->role === 'admin' ? 'Semua Transaksi' : 'Transaksi Penjualan';
    }
}