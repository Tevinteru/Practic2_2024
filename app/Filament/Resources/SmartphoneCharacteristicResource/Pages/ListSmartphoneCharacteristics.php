<?php

namespace App\Filament\Resources\SmartphoneCharacteristicResource\Pages;

use App\Filament\Resources\SmartphoneCharacteristicResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSmartphoneCharacteristics extends ListRecords
{
    protected static string $resource = SmartphoneCharacteristicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
