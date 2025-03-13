<?php
namespace App\Exports;

use App\Models\ProgramKerja;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Database\Eloquent\Collection;

class ProgramKerjaExport implements WithMultipleSheets
{
    protected $records;

    public function __construct(Collection $records)
    {
        $this->records = $records;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->records as $record) {
            // Ensure sheets are created even if no Rencana Anggaran Belanja exists
            $sheets[] = new ProgramKerjaSheet($record);
        }

        return $sheets;
    }
}
