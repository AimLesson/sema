<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Collection;

class EmptySheet implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        return collect([['No data available']]);
    }

    public function headings(): array
    {
        return ['Program Kerja', 'No records found'];
    }

    public function title(): string
    {
        return 'No Data';
    }
}
