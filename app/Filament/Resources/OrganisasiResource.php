<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganisasiResource\Pages;
use App\Models\Organisasi;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class OrganisasiResource extends Resource
{
    protected static ?string $model = Organisasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Organisasi';
    protected static ?string $pluralLabel = 'Organisasi';
    protected static ?string $slug = 'organisasi';
    // protected static ?string $navigationGroup = 'Struktur Organisasi dan Kegiatan';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Organisasi')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->label('ID'),
                TextColumn::make('name')->sortable()->searchable()->label('Nama Organisasi'),
                TextColumn::make('created_at')->label('Dibuat')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrganisasis::route('/'),
            // 'create' => Pages\CreateOrganisasi::route('/create'),
            // 'edit' => Pages\EditOrganisasi::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasAnyRole(['Superadmin','Ketua Senat Mahasiswa']);
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->hasAnyRole(['Superadmin', 'Ketua Senat Mahasiswa']);
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->hasAnyRole(['Superadmin','Ketua Senat Mahasiswa']);
    }
}
