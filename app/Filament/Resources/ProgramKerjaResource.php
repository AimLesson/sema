<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ProgramKerja;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProgramKerjaResource\Pages;
use App\Filament\Resources\ProgramKerjaResource\RelationManagers;

class ProgramKerjaResource extends Resource
{
    protected static ?string $model = ProgramKerja::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $pluralLabel = 'Program Kerja';
    // protected static ?string $navigationGroup = 'Manajemen Program Kerja';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Nama Program')->required()->maxLength(255),
                DatePicker::make('date')->label('Tanggal')->required(),
                Select::make('organisasi_id')->label('Organisasi')->relationship('organisasi', 'name')->searchable()->preload()->required(),
                Select::make('departement_id')->label('Departemen')->relationship('departement', 'name')->searchable()->preload()->required(),
                TextArea::make('description')->label('Deskripsi')->required(),
                TextInput::make('total_budget')->label('Total Anggaran')->disabled(),
                TextInput::make('self_budget')->label('Anggaran Mandiri')->disabled(),
                TextInput::make('proposal_budget')->label('Anggaran Proposal')->disabled(),

                Repeater::make('rencanaAnggaranBelanja')->relationship('rencanaAnggaranBelanja')->label('Rencana Anggaran Belanja')->columnSpanFull()->nullable()->collapsed()
                    ->schema([
                        TextInput::make('name')->label('Item')->required(),
                        Select::make('kategori')->label('Kategori')->options(['income' => 'Anggaran Mandiri','outcome' => 'Anggaran Proposal',])->required()->reactive(),
                        Select::make('divisi_id')->label('Divisi')->relationship('divisi', 'name')->searchable()->preload()->required(),
                        TextInput::make('qty')->default(1)->required()->reactive()
                            ->afterStateUpdated(function ($set, $state, $get) {
                                $unitPrice = $get('unit_price') ?? 0;
                                $set('unit_total', $state * $unitPrice);

                                // Update Total Anggaran Divisi dynamically
                                $divisiId = $get('divisi_id') ?? null;
                                if ($divisiId) {
                                    $totalDivisi = collect($get('../../rencanaAnggaranBelanja'))
                                        ->where('divisi_id', $divisiId)
                                        ->sum('unit_total');
                                    $set('total_price', $totalDivisi);
                                }
                            }),
                        TextInput::make('unit')->label('Satuan')->required(),
                        TextInput::make('unit_price')->required()->reactive()
                            ->afterStateUpdated(function ($set, $state, $get) {
                                $qty = $get('qty') ?? 0;
                                $set('unit_total', $state * $qty);

                                // Update Total Anggaran Divisi dynamically
                                $divisiId = $get('divisi_id') ?? null;
                                if ($divisiId) {
                                    $totalDivisi = collect($get('../../rencanaAnggaranBelanja'))
                                        ->where('divisi_id', $divisiId)
                                        ->sum('unit_total');
                                    $set('total_price', $totalDivisi);
                                }
                            }),
                        TextInput::make('unit_total') ->label('Total Harga')->default(0),
                        TextInput::make('total_price')->label('Total Anggaran Divisi')->default(0),
                    ])
                    ->columns(4)
                    ->addActionLabel('Tambah Item')
                    ->reorderableWithButtons()
                    ->reorderableWithDragAndDrop(false)
                    ->cloneable()
                    ->itemLabel(fn (array $state): ?string => isset($state['name']) && isset($state['unit_total']) ? $state['name'] . ' - Rp ' . number_format($state['unit_total'], 0, ',', '.') : null)
                    ->afterStateUpdated(function ($set, $state) {
                        // Auto-update Anggaran Mandiri, Proposal, and Total Anggaran
                        $selfBudget = collect($state)->where('kategori', 'income')->sum('unit_total');
                        $proposalBudget = collect($state)->where('kategori', 'outcome')->sum('unit_total');
                        $set('self_budget', $selfBudget);
                        $set('proposal_budget', $proposalBudget);
                        $set('total_budget', $selfBudget + $proposalBudget);
                    }),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable()->label('Nama Program'),
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
                TextColumn::make('date')->sortable()->date()->label('Tanggal'),
                TextColumn::make('organisasi.name')->label('Organisasi')->sortable(),
                TextColumn::make('departement.name')->label('Departemen')->sortable(),
                TextColumn::make('self_budget')->label('Anggaran Mandiri')->money('IDR'),
                TextColumn::make('proposal_budget')->label('Anggaran Proposal')->money('IDR'),
                TextColumn::make('total_budget')->label('Total Anggaran')->money('IDR'),
                TextColumn::make('created_at')->label('Dibuat')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make()->modalHeading('Detail Program Kerja')->modalWidth('5xl')->form([
                    Grid::make(2)->schema([
                        TextInput::make('name')->label('Nama Program')->required()->maxLength(255),
                        DatePicker::make('date')->label('Tanggal Kegiatan')->required(),
                        Select::make('organisasi_id')->label('Organisasi')->relationship('organisasi', 'name')->searchable()->preload()->required(),
                        TextInput::make('total_budget')->label('Total Anggaran')->disabled()->prefix('Rp')->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')),
                        TextInput::make('self_budget')->label('Anggaran Mandiri')->disabled()->prefix('Rp')->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')),
                        TextInput::make('proposal_budget')->label('Anggaran Proposal')->disabled()->prefix('Rp')->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')),
                        TextArea::make('approval.wakil_ketua_bidang_2_notes')->label('Catatan Wakil Ketua Bidang 2')->disabled()->columnSpanFull(),
                        TextArea::make('approval.wakil_ketua_bidang_3_notes')->label('Catatan Wakil Ketua Bidang 3')->disabled()->columnSpanFull(),
                        TextArea::make('approval.ketua_senat_mahasiswa_notes')->label('Catatan Ketua Senat Mahasiswa')->disabled()->columnSpanFull(),
                        Repeater::make('rencanaAnggaranBelanja')->relationship('rencanaAnggaranBelanja')->label('Rencana Anggaran Belanja')->columnSpanFull()->nullable()->collapsed()
                        ->schema([
                            TextInput::make('name')->label('Item')->required(),
                            Select::make('kategori')->label('Kategori')->options(['income' => 'Anggaran Mandiri','outcome' => 'Anggaran Proposal',])->required()->reactive(),
                            Select::make('divisi_id')->label('Divisi')->relationship('divisi', 'name')->searchable()->preload()->required(),
                            TextInput::make('qty')->default(1)->required()->reactive()
                                ->afterStateUpdated(function ($set, $state, $get) {
                                    $unitPrice = $get('unit_price') ?? 0;
                                    $set('unit_total', $state * $unitPrice);

                                    // Update Total Anggaran Divisi dynamically
                                    $divisiId = $get('divisi_id') ?? null;
                                    if ($divisiId) {
                                        $totalDivisi = collect($get('../../rencanaAnggaranBelanja'))
                                            ->where('divisi_id', $divisiId)
                                            ->sum('unit_total');
                                        $set('total_price', $totalDivisi);
                                    }
                                }),
                            TextInput::make('unit')->label('Satuan')->required(),
                            TextInput::make('unit_price')->required()->reactive()
                                ->afterStateUpdated(function ($set, $state, $get) {
                                    $qty = $get('qty') ?? 0;
                                    $set('unit_total', $state * $qty);

                                    // Update Total Anggaran Divisi dynamically
                                    $divisiId = $get('divisi_id') ?? null;
                                    if ($divisiId) {
                                        $totalDivisi = collect($get('../../rencanaAnggaranBelanja'))
                                            ->where('divisi_id', $divisiId)
                                            ->sum('unit_total');
                                        $set('total_price', $totalDivisi);
                                    }
                                }),
                            TextInput::make('unit_total') ->label('Total Harga')->default(0),
                            TextInput::make('total_price')->label('Total Anggaran Divisi')->default(0),
                        ])
                        ->columns(4)
                        ->addActionLabel('Tambah Item')
                        ->reorderableWithButtons()
                        ->reorderableWithDragAndDrop(false)
                        ->cloneable()
                        ->itemLabel(fn (array $state): ?string => isset($state['name']) && isset($state['unit_total']) ? $state['name'] . ' - Rp ' . number_format($state['unit_total'], 0, ',', '.') : null)
                        ->afterStateUpdated(function ($set, $state) {
                            // Auto-update Anggaran Mandiri, Proposal, and Total Anggaran
                            $selfBudget = collect($state)->where('kategori', 'income')->sum('unit_total');
                            $proposalBudget = collect($state)->where('kategori', 'outcome')->sum('unit_total');
                            $set('self_budget', $selfBudget);
                            $set('proposal_budget', $proposalBudget);
                            $set('total_budget', $selfBudget + $proposalBudget);
                        }),
                    ]),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProgramKerjas::route('/'),
            'create' => Pages\CreateProgramKerja::route('/create'),
            'edit' => Pages\EditProgramKerja::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasAnyRole(['Superadmin', 'Sekretaris', 'Bendahara']);
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();
        return $user->hasAnyRole(['Superadmin', 'Sekretaris', 'Bendahara']) || ($user->hasRole('User') && $user->organisasi_id === $record->organisasi_id);
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->hasAnyRole(['Superadmin', 'Sekretaris', 'Bendahara']);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
