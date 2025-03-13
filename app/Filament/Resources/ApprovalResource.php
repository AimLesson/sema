<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApprovalResource\Pages;
use App\Models\Approval;
use Filament\Forms;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextArea;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class ApprovalResource extends Resource
{
    protected static ?string $model = Approval::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationLabel = 'Approvals';
    protected static ?string $pluralLabel = 'Approvals';
    // protected static ?string $navigationGroup = 'Administrasi';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Toggle::make('wakil_ketua_bidang_2_approval')
                    ->label('Wakil Ketua Bidang 2 Approval')
                    ->visible(fn () => auth()->user()->hasRole('Wakil Ketua Bidang 2')), // ✅ Correct Role Name

                TextArea::make('wakil_ketua_bidang_2_notes')
                    ->label('Notes Wakil Ketua Bidang 2')
                    ->visible(fn () => auth()->user()->hasRole('Wakil Ketua Bidang 2')), // ✅ Correct Role Name

                Toggle::make('wakil_ketua_bidang_3_approval')
                    ->label('Wakil Ketua Bidang 3 Approval')
                    ->visible(fn () => auth()->user()->hasRole('Wakil Ketua Bidang 3')),

                TextArea::make('wakil_ketua_bidang_3_notes')
                    ->label('Notes Wakil Ketua Bidang 3')
                    ->visible(fn () => auth()->user()->hasRole('Wakil Ketua Bidang 3')),

                Toggle::make('ketua_senat_mahasiswa_approval')
                    ->label('Ketua Senat Mahasiswa Approval')
                    ->visible(fn () => auth()->user()->hasRole('Ketua Senat Mahasiswa')),

                TextArea::make('ketua_senat_mahasiswa_notes')
                    ->label('Notes Ketua Senat Mahasiswa')
                    ->visible(fn () => auth()->user()->hasRole('Ketua Senat Mahasiswa')),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('programKerja.name')
                    ->label('Program Kerja')
                    ->searchable()
                    ->alignCenter(),

                IconColumn::make('wakil_ketua_bidang_2_approval')
                    ->label('Wakil Ketua Bidang 2')
                    ->alignCenter()
                    ->icon(fn ($state) => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn ($state) => $state ? 'success' : 'danger'), // ✅ Hide the number // ✅ Green for approved, red for rejected

                IconColumn::make('wakil_ketua_bidang_3_approval')
                    ->label('Wakil Ketua Bidang 3')
                    ->alignCenter()
                    ->icon(fn ($state) => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn ($state) => $state ? 'success' : 'danger'), // ✅ Hide the number

                IconColumn::make('ketua_senat_mahasiswa_approval')
                    ->label('Ketua Senat Mahasiswa')
                    ->alignCenter()
                    ->icon(fn ($state) => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn ($state) => $state ? 'success' : 'danger'),
            ])

            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn () => auth()->user()->hasRole('Wakil Ketua Bidang 2') || auth()->user()->hasRole('Wakil Ketua Bidang 3') || auth()->user()->hasRole('Ketua Senat Mahasiswa')),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApprovals::route('/'),
            // 'edit' => Pages\EditApproval::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyRole(['Superadmin', 'Wakil Ketua Bidang 3', 'Ketua Senat Mahasiswa', 'Wakil Ketua Bidang 2', 'Sekretaris']);
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasRole('Superadmin');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->hasAnyRole(['Ketua Senat Mahasiswa', 'Wakil Ketua Bidang 3', 'Wakil Ketua Bidang 2']);
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->hasRole('Superadmin');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasAnyRole([
            'Superadmin',
            'Wakil Ketua Bidang 2',
            'Wakil Ketua Bidang 3',
            'Ketua Senat Mahasiswa',
        ]);
    }
}
