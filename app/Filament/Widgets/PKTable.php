<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use App\Models\ProgramKerja;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class PKTable extends BaseWidget
{
    protected static ?string $model = ProgramKerja::class;

    protected static ?string $heading = 'Program Kerja Terdekat';

    protected static ?int $sort = 3;

    protected static ?string $pluralLabel = 'Program Kerja';

    public function query()
    {
        return ProgramKerja::query()
            ->with('organisasi', 'departement', 'approval')
            ->orderBy('date', 'asc') // ✅ Sort by nearest date
            ->limit(5); // ✅ Show only the top 5 nearest
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->query()) // ✅ Ensure the table has a query
            ->columns([
                TextColumn::make('name')->label('Nama Program'),
                IconColumn::make('approval_status')->label('Status Persetujuan')->alignCenter()
                    ->getStateUsing(fn ($record) => match (true) {
                        !$record->approval => 'not_submitted',
                        $record->approval->wakil_ketua_bidang_2_approval &&
                            $record->approval->wakil_ketua_bidang_3_approval &&
                            $record->approval->ketua_senat_mahasiswa_approval => 'approved',
                        $record->approval->wakil_ketua_bidang_2_approval === false ||
                            $record->approval->wakil_ketua_bidang_3_approval === false ||
                            $record->approval->ketua_senat_mahasiswa_approval === false => 'rejected',
                        default => 'pending' // ⏳ Waiting for approvals
                    })
                    ->icon(fn ($state) => match ($state) {
                        'approved' => 'heroicon-o-check-circle',
                        'rejected' => 'heroicon-o-x-circle',
                        'pending' => 'heroicon-o-clock',
                        'not_submitted' => 'heroicon-o-question-mark-circle',
                    })
                    ->color(fn ($state) => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'pending' => 'warning',
                        'not_submitted' => 'gray',
                    })
                    ->tooltip(
                        fn ($record) => $record->approval
                            ? (
                                !empty($record->approval->wakil_ketua_bidang_2_notes) ||
                                !empty($record->approval->wakil_ketua_bidang_3_notes) ||
                                !empty($record->approval->ketua_senat_mahasiswa_notes)
                                ? "Ada Catatan"
                                : "Tidak Ada Catatan"
                            )
                            : 'Belum ada persetujuan.'
                    ),
                TextColumn::make('date')->date()->label('Tanggal'),
                TextColumn::make('organisasi.name')->label('Organisasi'),
                TextColumn::make('departement.name')->label('Departemen'),
                TextColumn::make('total_budget')->label('Total Anggaran')->money('IDR'),
            ])
            ->emptyStateHeading('Belum ada Program Kerja') // ✅ Show message when no data
            ->emptyStateDescription('Silakan tambahkan Program Kerja baru jika diperlukan.')
            ->emptyStateIcon('heroicon-o-calendar')
            ->paginated(false); // Optional: Icon for empty state
    }
}
