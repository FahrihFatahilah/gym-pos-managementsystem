<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MembersExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    protected $members;

    public function __construct($members)
    {
        $this->members = $members;
    }

    public function collection()
    {
        return $this->members->map(function ($member) {
            return [
                'nama' => $member->name,
                'phone' => $member->phone,
                'email' => $member->email ?? '-',
                'status' => $member->status == 'active' ? 'AKTIF' : 'EXPIRED',
                'tipe_membership' => $member->activeMembership ? ucfirst($member->activeMembership->type) : '-',
                'berakhir' => $member->activeMembership ? $member->activeMembership->end_date->format('d/m/Y') : '-'
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'No. HP',
            'Email',
            'Status',
            'Tipe Membership',
            'Berakhir'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '10b981']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 15,
            'C' => 25,
            'D' => 12,
            'E' => 18,
            'F' => 15,
        ];
    }
}