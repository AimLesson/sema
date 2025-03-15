<?php

namespace App\Filament\Widgets;

use App\Models\ProgramKerja;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use App\Filament\Resources\ProgramKerjaResource\Pages\ListProgramKerjas;

class RABOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    use InteractsWithPageTable;

    protected function getHeading(): ?string
    {
        return 'Program Kerja';
    }

    protected function getStats(): array
    {
        // ✅ Get today's and yesterday's data for comparison
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // 🔹 Count "Belum Disetujui" (Not Approved) Today & Yesterday
        $notApprovedToday = ProgramKerja::whereDoesntHave('approval', function ($query) {
            $query->where('wakil_ketua_bidang_2_approval', true)
                ->where('wakil_ketua_bidang_3_approval', true)
                ->where('ketua_senat_mahasiswa_approval', true);
        })->count();

        $notApprovedYesterday = ProgramKerja::whereDoesntHave('approval', function ($query) {
            $query->where('wakil_ketua_bidang_2_approval', true)
                ->where('wakil_ketua_bidang_3_approval', true)
                ->where('ketua_senat_mahasiswa_approval', true);
        })->whereDate('created_at', '<=', $yesterday)->count();

        // 🔹 Count "Disetujui" (Approved) Today & Yesterday
        $approvedToday = ProgramKerja::whereHas('approval', function ($query) {
            $query->where('wakil_ketua_bidang_2_approval', true)
                ->where('wakil_ketua_bidang_3_approval', true)
                ->where('ketua_senat_mahasiswa_approval', true);
        })->count();

        $approvedYesterday = ProgramKerja::whereHas('approval', function ($query) {
            $query->where('wakil_ketua_bidang_2_approval', true)
                ->where('wakil_ketua_bidang_3_approval', true)
                ->where('ketua_senat_mahasiswa_approval', true);
        })->whereDate('created_at', '<=', $yesterday)->count();

        // 🔹 Get Movement Difference
        $notApprovedDiff = $notApprovedToday - $notApprovedYesterday;
        $approvedDiff = $approvedToday - $approvedYesterday;

        return [
            // 🔴 Belum Disetujui (Not Approved)
            Stat::make('Belum Disetujui', $notApprovedToday)
                ->description($this->getMovementDescription($notApprovedDiff))
                ->descriptionIcon($this->getMovementIcon($notApprovedDiff))
                ->color($this->getMovementColor($notApprovedDiff)),

            // 🟢 Disetujui (Approved)
            Stat::make('Disetujui', $approvedToday)
                ->description($this->getMovementDescription($approvedDiff))
                ->descriptionIcon($this->getMovementIcon($approvedDiff))
                ->color($this->getMovementColor($approvedDiff)),

            // 🟠 Placeholder (Future Change)
            Stat::make('Terlaksana', $approvedToday)
                ->description($this->getMovementDescription($approvedDiff))
                ->descriptionIcon($this->getMovementIcon($approvedDiff))
                ->color($this->getMovementColor($approvedDiff)),

            // 🔵 Total Disetujui
            Stat::make('Belum Terlaksana', $approvedToday)
                ->description($this->getMovementDescription($approvedDiff))
                ->descriptionIcon($this->getMovementIcon($approvedDiff))
                ->color($this->getMovementColor($approvedDiff)),
        ];
    }

    protected function getTablePage(): string
    {
        return ListProgramKerjas::class;
    }

    // 🔹 Helper function: Get Description with Count Movement
    protected function getMovementDescription(int $diff): string
    {
        if ($diff > 0) {
            return "$diff lebih banyak dari kemarin";
        } elseif ($diff < 0) {
            return abs($diff) . " lebih sedikit dari kemarin";
        }
        return "Tidak ada perubahan";
    }

    // 🔹 Helper function: Get Icon Based on Movement
    protected function getMovementIcon(int $diff): string
    {
        if ($diff > 0) {
            return 'heroicon-m-arrow-trending-up';
        } elseif ($diff < 0) {
            return 'heroicon-m-arrow-trending-down';
        }
        return 'heroicon-m-minus-circle';
    }

    // 🔹 Helper function: Get Color Based on Movement
    protected function getMovementColor(int $diff): string
    {
        if ($diff > 0) {
            return 'success';
        } elseif ($diff < 0) {
            return 'danger';
        }
        return 'gray';
    }
}
