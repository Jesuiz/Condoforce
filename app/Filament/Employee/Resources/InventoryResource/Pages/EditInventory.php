<?php

namespace App\Filament\Employee\Resources\InventoryResource\Pages;

use App\Filament\Employee\Resources\InventoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventory extends EditRecord
{
    protected static string $resource = InventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
