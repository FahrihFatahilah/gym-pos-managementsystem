<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SalesExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    protected $transactions;

    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }

    public function collection()
    {
        return $this->transactions->map(function ($transaction) {
            return [
                'tanggal' => $transaction->created_at->format('d/m/Y H:i'),
                'kode_transaksi' => $transaction->transaction_code,
                'kasir' => $transaction->user->name ?? 'System',
                'metode_bayar' => $transaction->getPaymentMethodLabel(),
                'items' => $transaction->details->map(function($detail) {
                    return $detail->product->name . ' (' . $detail->quantity . 'x)';
                })->implode(', '),
                'total' => $transaction->total_amount
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Kode Transaksi',
            'Kasir',
            'Metode Bayar',
            'Items',
            'Total'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '2563eb']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 15,
            'D' => 15,
            'E' => 40,
            'F' => 15,
        ];
    }
}