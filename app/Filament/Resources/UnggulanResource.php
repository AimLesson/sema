<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Unggulan;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UnggulanResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UnggulanResource\RelationManagers;

class UnggulanResource extends Resource
{
    protected static ?string $model = Unggulan::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationLabel = 'Program Unggulan';
    protected static ?string $pluralLabel = 'Program Kerja Unggulan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Nama Unggulan')->required(),
                FileUpload::make('picture')->label('Gambar')->image()->directory('unggulan/images')->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama Unggulan')->sortable()->searchable(),
                ImageColumn::make('picture')->label('Gambar')->circular(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListUnggulans::route('/'),
            // 'create' => Pages\CreateUnggulan::route('/create'),
            // 'view' => Pages\ViewUnggulan::route('/{record}'),
            // 'edit' => Pages\EditUnggulan::route('/{record}/edit'),
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
