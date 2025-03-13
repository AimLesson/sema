<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProgramKerja;
use App\Models\Approval;

class ApprovalSeeder extends Seeder
{
    public function run()
    {
        // Get all ProgramKerja entries that do not have an Approval yet
        $programKerjas = ProgramKerja::doesntHave('approval')->get();

        foreach ($programKerjas as $programKerja) {
            Approval::create([
                'program_kerja_id' => $programKerja->id,
            ]);
        }
    }
}

