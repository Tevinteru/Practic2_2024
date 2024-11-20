<?php

namespace App\Filament\Resources\SmartphoneResource\Pages;

use App\Filament\Resources\SmartphoneResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSmartphones extends ListRecords
{
    protected static string $resource = SmartphoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
