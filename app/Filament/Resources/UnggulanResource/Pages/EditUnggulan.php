<?php

namespace App\Filament\Resources\UnggulanResource\Pages;

use App\Filament\Resources\UnggulanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUnggulan extends EditRecord
{
    protected static string $resource = UnggulanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
