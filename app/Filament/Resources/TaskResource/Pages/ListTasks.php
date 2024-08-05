<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Models\Task;
use App\Filament\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

class ListTasks extends ListRecords
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Creamos y notificamos una nueva actividad
            Actions\CreateAction::make()->label('Nueva Actividad')
                ->color('info')->icon('heroicon-o-arrow-up-circle')
                ->action(function () {
                    $recipient = auth()->user();
                    Notification::make()
                        ->title('Actividad creada correctamente')
                        ->icon('heroicon-o-arrow-up-circle')
                        ->info()
                        ->sendToDatabase($recipient)
                        ->send();
            }),

            // Creamos y notificamos el cambio de status de una actividad
            Actions\Action::make('changeStatus')->label('Status')
                ->color('gray')->icon('heroicon-o-arrow-path')
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
                        ->title('Status actualizado correctamente')
                        ->icon('heroicon-o-arrow-path')
                        ->success()
                        ->sendToDatabase($recipient)
                        ->send();
            }),

            // Creamos y notificamos el cambio de limite de una actividad
            Actions\Action::make('changeLimit')->label('Límite')
                ->color('gray')->icon('heroicon-o-arrow-path')
                ->form([
                    Select::make('name')->label('Selecciona la actividad a modificar')
                        ->required()
                        ->placeholder('Selecciona una opción')
                        ->options(Task::query()->pluck('name', 'id')),

                    TextInput::make('time_limit')->label('¿Cuánto será el nuevo límite de horas?')
                        ->numeric()
                        ->required()
                        ->minValue(1)
                        ->maxValue(744)
                        ->suffix('Horas'),
                ])
                ->action(function (array $data): void {
                    $task = Task::findOrFail($data['name']);
                    $task->time_limit = $data['time_limit'];
                    $task->save();

                    Notification::make()
                        ->title('Límite actualizado correctamente')
                        ->icon('heroicon-o-arrow-path')
                        ->success()
                        ->send();
            }),
        ];
    }
}
