<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    // protected static ?string $navigationGroup = 'Administrasi';

    protected static ?string $pluralLabel = 'Pengguna';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),

                TextInput::make('password')
                    ->password()
                    ->maxLength(255)
                    ->required()
                    ->hiddenOn('edit'), // Agar password tidak muncul saat edit

                Select::make('role_id')
                    ->label('Role')
                    ->relationship('role', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('organisasi_id')
                    ->relationship('organisasi', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('departement_id')
                    ->relationship('departement', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('role.name')->label('Role')->sortable(),
                TextColumn::make('organisasi.name')->label('Organisasi')->sortable(),
                TextColumn::make('departement.name')->label('Departemen')->sortable(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
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
        return $user->hasAnyRole(['Superadmin', 'Sekretaris']);
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->hasAnyRole(['Superadmin', 'Sekretaris']);
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasAnyRole([
            'Superadmin',
            'Sekretaris',
            'Ketua Senat Mahasiswa',
        ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
