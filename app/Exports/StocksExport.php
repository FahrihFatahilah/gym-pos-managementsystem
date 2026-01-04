<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class StocksExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    public function collection()
    {
        return $this->products->map(function ($product) {
            return [
                'sku' => $product->sku,
                'nama' => $product->name,
                'stok' => $product->stock . ($product->stock < 10 ? ' (LOW)' : ''),
                'unit' => $product->unit,
                'harga_beli' => $product->purchase_price,
                'harga_jual' => $product->selling_price
            ];
        });
    }

    public function headings(): array
    {
        return [
            'SKU',
            'Nama Produk',
            'Stok',
            'Unit',
            'Harga Beli',
            'Harga Jual'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'f59e0b']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 30,
            'C' => 12,
            'D' => 10,
            'E' => 15,
            'F' => 15,
        ];
    }
}