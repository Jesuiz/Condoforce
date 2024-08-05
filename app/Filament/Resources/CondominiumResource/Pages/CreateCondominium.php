<?php

namespace App\Filament\Resources\CondominiumResource\Pages;

use App\Filament\Resources\CondominiumResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;


class CreateCondominium extends CreateRecord
{
    protected static string $resource = CondominiumResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['condominium_id'] = Auth::user()->condominium_id;

        return $data;
    }
}
