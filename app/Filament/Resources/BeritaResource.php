<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Berita;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\BeritaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BeritaResource\RelationManagers;
use Filament\Forms\Components\RichEditor;

class BeritaResource extends Resource
{
    protected static ?string $model = Berita::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationLabel = 'Berita';
    protected static ?string $pluralLabel = 'Berita';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->label('Judul')->required()->columnSpanFull(),
                FileUpload::make('picture')->label('Gambar')->image()->directory('berita/images')->nullable()->columnSpanFull(),
                RichEditor::make('content')->label('Konten')->required()->columnSpanFull(),
                TextInput::make('author')->label('Penulis')->default(auth()->user()->name)->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Judul')->sortable()->searchable(),
                TextColumn::make('author')->label('Penulis')->sortable(),
                ImageColumn::make('picture')->label('Gambar')->circular()->alignCenter(),
                ToggleColumn::make('is_published')->label('Dipublikasikan')->sortable()->alignCenter(),
                TextColumn::make('timestamp')->label('Waktu Publikasi')->sortable(),

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
            'index' => Pages\ListBeritas::route('/'),
            // 'create' => Pages\CreateBerita::route('/create'),
            // 'view' => Pages\ViewBerita::route('/{record}'),
            // 'edit' => Pages\EditBerita::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasAnyRole(['Superadmin', 'Sekretaris']);
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();
        return $user->hasAnyRole(['Superadmin', 'Sekretaris','Ketua Senat Mahasiswa']);
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->hasAnyRole(['Superadmin', 'Sekretaris']);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'author', 'is_published'];
    }
}
