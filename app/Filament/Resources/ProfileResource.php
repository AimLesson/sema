<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Profile;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProfileResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProfileResource\RelationManagers;

class ProfileResource extends Resource
{
    protected static ?string $model = Profile::class;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';
    protected static ?string $navigationLabel = 'Halaman Utama';
    protected static ?string $pluralLabel = 'Halaman Utama';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('full_name')->label('Nama Institusi')->required(),
                TextInput::make('nick_name')->label('Nama Organisasi')->required(),
                FileUpload::make('logo')->label('Logo Organisasi')->image()->directory('profiles/logos')->nullable()->columnSpanFull(),
                RichEditor::make('description')->label('Deskripsi Organisasi')->nullable()->columnSpanFull(),
                TextInput::make('address')->label('Alamat Organisasi')->nullable(),
                TextInput::make('email')->label('Email Organisasi')->email()->unique(ignoreRecord: true)->nullable(),
                TextInput::make('phone_number')->label('Nomor Handphone Organisasi')->nullable(),
                TextInput::make('instagram_account_link')->label('Instagram Organisasi')->nullable(),
                TextInput::make('tiktok_account_link')->label('TikTok Organisasi')->nullable(),
                TextInput::make('whatsapp_account_link')->label('WhatsApp Organisasi')->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nick_name')->label('Nama Organisasi')->sortable(),
                TextColumn::make('full_name')->label('Nama Institusi')->sortable()->searchable(),
                ImageColumn::make('logo')->label('Logo Organisasi')->circular(),
                TextColumn::make('email')->label('Email Organisasi')->sortable()->searchable(),
                TextColumn::make('phone_number')->label('Phone Organisasi')->sortable(),
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
                //
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
            'index' => Pages\ListProfiles::route('/'),
            // 'create' => Pages\CreateProfile::route('/create'),
            // 'view' => Pages\ViewProfile::route('/{record}'),
            // 'edit' => Pages\EditProfile::route('/{record}/edit'),
        ];
    }
}
