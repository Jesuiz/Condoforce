<?php

namespace App\Filament\Employee\Resources\TaskResource\Pages;

use App\Models\Task;
use App\Models\User;

use App\Filament\Employee\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Livewire\Component;


class ListTasks extends ListRecords
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Nueva Actividad')
                ->color('info')->icon('heroicon-o-arrow-up-circle')
                ->action(function () {
                    Notification::make()
                        ->title('Actividad creada correctamente')
                        ->icon('heroicon-o-arrow-up-circle')
                        ->success()
                        ->send();
                })
            ];

        return [
            Actions\Action::make('changeLimit')->label('Cambiar Límite')
                ->color('warning')->icon('heroicon-o-arrow-path')
                ->form([
                    Select::make('name')->label('Selecciona la actividad a modificar')
                        ->required()->options(Task::query()->pluck('name', 'id'))->placeholder('Selecciona una opción'),
                    TextInput::make('time_limit')->label('Define el nuevo límite de horas')
                        ->required()->numeric()->minValue(1)->maxValue(744)->suffix('Horas'),
                ])
                ->action(function () {
                    $user = Auth::user();
                    $tasks = Task::all()->pluck('name', 'id');

                    Notification::make()
                        ->title('Se cambió el límite correctamente')
                        ->icon('heroicon-o-arrow-up-circle')
                        ->success()->send();
                })
            ];
    }
}
