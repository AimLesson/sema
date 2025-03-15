<?php

namespace App\Filament\Resources\UnggulanResource\Pages;

use App\Filament\Resources\UnggulanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUnggulans extends ListRecords
{
    protected static string $resource = UnggulanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
