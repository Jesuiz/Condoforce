<?php

namespace App\Filament\Resources\CondominiumResource\Pages;

use App\Filament\Resources\CondominiumResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCondominium extends EditRecord
{
    protected static string $resource = CondominiumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
