<?php
namespace App\Exports;

use App\Models\ProgramKerja;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProgramKerjaSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, WithColumnFormatting, WithStyles
{
    protected $record;

    public function __construct(ProgramKerja $record)
    {
        $this->record = $record;
    }

    public function collection()
    {
        return $this->record->rencanaAnggaranBelanja;
    }

    public function headings(): array
    {
        return [
            // ✅ Main Title (Merged Across Columns)
            ["Program Kerja - " . $this->record->name . " - " . \Carbon\Carbon::parse($this->record->date)->format('d-m-Y')],
            [],
            // ✅ Second Row: Organisasi and Budget Info
            ["Organisasi", $this->record->organisasi->name ?? '', "Total Anggaran", $this->record->total_budget, "Anggaran Mandiri", $this->record->self_budget, "Anggaran Proposal", $this->record->proposal_budget],
            [],
            // ✅ Rencana Anggaran Belanja Header
            ["Rencana Anggaran Belanja"],
            ["NO", "Item", "Kategori", "Divisi", "Qty", "Satuan", "Harga Satuan", "Total Harga"]
        ];
    }

    public function map($row): array
    {
        static $rowIndex = 1; // ✅ Auto-numbering

        return [
            $rowIndex++,  // ✅ Numbering column
            $row->name,
            $row->kategori == 'income' ? 'Anggaran Mandiri' : 'Anggaran Proposal',
            $row->divisi->name ?? '',
            $row->qty,
            $row->unit,
            (float) $row->unit_price,
            (float) $row->unit_total,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Harga Satuan
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Total Harga
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // ✅ Merge Main Title Across Columns
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->mergeCells('A5:H5');

        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // ✅ Bold and center the main headers
        $sheet->getStyle('A3:H3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A3')->getFont()->setBold(true);
        $sheet->getStyle('C3')->getFont()->setBold(true);
        $sheet->getStyle('E3')->getFont()->setBold(true);
        $sheet->getStyle('G3')->getFont()->setBold(true);
        $sheet->getStyle('A5:H5')->getFont()->setBold(true);
        $sheet->getStyle('A6:H6')->getFont()->setBold(true);
        $sheet->getStyle('A5:H5')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A6:H6')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A7:A10')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B7:B10')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('C7:C10')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('D7:D10')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('E7:E10')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('F7:F10')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('G7:G10')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('H7:H10')->getAlignment()->setHorizontal('center');

        // ✅ Set background color for the headers
        $sheet->getStyle('A5:H5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFC000');
        $sheet->getStyle('A6:H6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF99');

        return [];
    }

    public function title(): string
    {
        return $this->record->name;
    }
}
