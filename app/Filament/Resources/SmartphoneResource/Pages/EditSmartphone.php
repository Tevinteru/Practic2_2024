<?php

namespace App\Filament\Resources\SmartphoneResource\Pages;

use App\Filament\Resources\SmartphoneResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSmartphone extends EditRecord
{
    protected static string $resource = SmartphoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
