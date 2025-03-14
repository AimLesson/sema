<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Divisi;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\DivisiResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DivisiResource\RelationManagers;

class DivisiResource extends Resource
{
    protected static ?string $model = Divisi::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Divisi Kegiatan';
    protected static ?string $pluralLabel = 'Divisi Kegiatan';
    protected static ?string $slug = 'Divisi Kegiatan';
    // protected static ?string $navigationGroup = 'Struktur Organisasi dan Kegiatan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Divisi')
                    ->required()
                    ->maxLength(255),

                Select::make('program_kerja_id')
                    ->label('Program Kerja')
                    ->relationship('programKerja', 'name') // Make sure this matches the model's function name
                    ->searchable()
                    ->preload()
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->label('ID'),
                TextColumn::make('name')->sortable()->searchable()->label('Nama Divisi'),
                TextColumn::make('programKerja.name')->label('Program Kerja')->sortable(),
                TextColumn::make('created_at')->label('Dibuat')->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('program_kerja_id')
                    ->label('Program Kerja')
                    ->relationship('programKerja', 'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListDivisis::route('/'),
            'create' => Pages\CreateDivisi::route('/create'),
            'edit' => Pages\EditDivisi::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasAnyRole(['Superadmin','Sekretaris','Ketua Senat Mahasiswa']);
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->hasAnyRole(['Superadmin', 'Sekretaris','Ketua Senat Mahasiswa']);
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->hasAnyRole(['Superadmin','Sekretaris','Ketua Senat Mahasiswa']);
    }

}
