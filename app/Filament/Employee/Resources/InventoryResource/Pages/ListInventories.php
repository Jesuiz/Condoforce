<?php

namespace App\Filament\Employee\Resources\InventoryResource\Pages;

use App\Models\Inventory;
use App\Filament\Employee\Resources\InventoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;


class ListInventories extends ListRecords
{
    protected static string $resource = InventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Nuevo Producto')
                ->color('info')->icon('heroicon-o-arrow-up-circle')
                ->action(function () {
                    $recipient = auth()->user();
                    Notification::make()
                        ->title('Producto creado correctamente')
                        ->icon('heroicon-o-arrow-up-circle')
                        ->info()
                        ->sendToDatabase($recipient)
                        ->send();
                }),

            Actions\Action::make('addUnits')->label('Añadir')
                    ->color('gray')->icon('heroicon-o-plus-circle')
                    ->form([
                        Select::make('name')->label('Elige el producto')
                            ->required()
                            ->placeholder('Selecciona una opción')
                            ->options(Inventory::query()->pluck('name', 'id')),

                        TextInput::make('units_to_add')->label('¿Cuántas unidades deseas añadir?')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(999),
                    ])
                    ->action(function (array $data): void {
                        $inventory = Inventory::findOrFail($data['name']);
                        $inventory->units + $data['units_to_add'];
                        $inventory->save();
                        
                        $recipient = auth()->user();
                        Notification::make()
                            ->title('Unidades añadidas correctamente')
                            ->icon('heroicon-o-plus-circle')
                            ->success()
                            ->sendToDatabase($recipient)
                            ->send();
                    }),

            Actions\Action::make('discountUnits')->label('Descontar')
                ->color('gray')->icon('heroicon-o-minus-circle')
                ->form([
                    Select::make('name')->label('Elige el producto')
                        ->required()
                        ->placeholder('Selecciona una opción')
                        ->options(Inventory::query()->pluck('name', 'id')),

                    TextInput::make('units_to_discount')->label('¿Cuántas unidades deseas descontar?')
                        ->numeric()
                        ->required()
                        ->minValue(1),
                ])
                ->action(function (array $data): void {
                    $inventory = Inventory::findOrFail($data['name']);
                    
                    if ($inventory->units < $data['units_to_discount']) {

                        $recipient = auth()->user();
                        Notification::make()
                            ->title('Error: No hay suficientes unidades')
                            ->danger()
                            ->success()
                            ->sendToDatabase($recipient)
                            ->send();
                        return;
                    }
                    $inventory->units - $data['units_to_discount'];
                    $inventory->save();

                    $recipient = auth()->user();
                    Notification::make()
                        ->title('Unidades descontadas correctamente')
                        ->icon('heroicon-o-minus-circle')
                        ->success()
                        ->sendToDatabase($recipient)
                        ->send();
                }),
        ];

    }
}

