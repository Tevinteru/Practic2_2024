<?php

namespace App\Filament\Resources\SmartphoneCharacteristicResource\Pages;

use App\Filament\Resources\SmartphoneCharacteristicResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSmartphoneCharacteristic extends EditRecord
{
    protected static string $resource = SmartphoneCharacteristicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
