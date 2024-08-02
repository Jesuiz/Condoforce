<?php

namespace App\Filament\Employee\Resources\ReportResource\Pages;

use App\Filament\Employee\Resources\ReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReports extends ListRecords
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Nuevo Reporte')
                ->color('info')->icon('heroicon-o-arrow-up-circle'),
        ];
    }
}
