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
            // Creamos y notificamos el cambio de status de una actividad
            Actions\Action::make('changeStatus')->label('Cambiar Status')
                ->color('info')->icon('heroicon-o-arrow-path')
                ->form([
                    Select::make('name')->label('Selecciona la actividad a modificar')
                        ->required()
                        ->placeholder('Selecciona una opción')
                        ->options(Task::query()->pluck('name', 'id')),

                    Select::make('status')->label('¿Cuánto será el nuevo status?')
                        ->required()
                        ->placeholder('Selecciona una opción')
                        ->options(['Asignado','En Desarrollo','Finalizado','Fallido']),
                ])
                ->action(function (array $data): void {
                    $task = Task::findOrFail($data['name']);
                    $task->status = $data['status']+1;
                    $task->save();

                    $recipient = auth()->user();
                    Notification::make()
                        ->title('Límite actualizado correctamente')
                        ->icon('heroicon-o-arrow-path')
                        ->success()
                        ->sendToDatabase($recipient)
                        ->send();
            }),
        ];
    }
}
