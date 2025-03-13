<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartementResource\Pages;
use App\Models\Departement;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class DepartementResource extends Resource
{
    protected static ?string $model = Departement::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Departemen';
    protected static ?string $pluralLabel = 'Departemen';
    protected static ?string $slug = 'departemen';
    // protected static ?string $navigationGroup = 'Struktur Organisasi dan Kegiatan';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Departemen')
                    ->required()
                    ->maxLength(255),

                Select::make('organisasi_id')
                    ->label('Organisasi')
                    ->relationship('organisasi', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->label('ID'),
                TextColumn::make('name')->sortable()->searchable()->label('Nama Departemen'),
                TextColumn::make('organisasi.name')->label('Organisasi')->sortable(),
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
            'index' => Pages\ListDepartements::route('/'),
            // 'create' => Pages\CreateDepartement::route('/create'),
            // 'edit' => Pages\EditDepartement::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasAnyRole(['Superadmin','Sekretaris']);
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->hasAnyRole(['Superadmin', 'Sekretaris']);
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->hasAnyRole(['Superadmin','Sekretaris']);
    }
}
