<?php

namespace App\Filament\Resources\CondominiumResource\Pages;

use App\Filament\Resources\CondominiumResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCondominium extends ListRecords
{
    protected static string $resource = CondominiumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Nuevo Condominio')
                ->color('info')->icon('heroicon-o-arrow-up-circle'),
        ];
    }
}
