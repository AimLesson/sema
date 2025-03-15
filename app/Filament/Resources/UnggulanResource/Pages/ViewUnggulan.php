<?php

namespace App\Filament\Resources\UnggulanResource\Pages;

use App\Filament\Resources\UnggulanResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUnggulan extends ViewRecord
{
    protected static string $resource = UnggulanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
