<?php

namespace App\Filament\Employee\Resources\TaskResource\Pages;

use App\Filament\Employee\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['status'] = 'Asignado';
        $data['finish'] = 0;
        $data['user_id'] = auth()->id();
        $data['condominium_id'] = Auth::user()->condominium_id;
        $data['report_id'] = null;
        $data['last_edited_by_id'] = auth()->id();
     
        return $data;
    }
}