<?php

namespace App\Filament\Resources\InventoryResource\Pages;

use App\Models\Inventory;
use App\Models\User;

use App\Filament\Employee\Resources\InventoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Form;
use Livewire\Component;

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
                    ->modalHeading('Añadir unidades de un Producto')
                    ->modalIcon('heroicon-o-plus-circle')
                    ->color('gray')->icon('heroicon-o-plus-circle')
                    ->form([
                        Select::make('name')->label('Elige el producto')
                            ->required()
                            ->placeholder('Selecciona una opción')
                            ->options(Inventory::query()->where('user_id', Auth::user()->id)->pluck('name', 'id')),

                        TextInput::make('units_to_add')->label('¿Cuántas unidades deseas añadir?')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(999),
                    ])
                    ->action(function (array $data): void {
                        $product = Inventory::findOrFail($data['name']);
                        $product->units += $data['units_to_add'];
                        $product->save();
    
                        $recipient = auth()->user();
                        Notification::make()
                            ->title('Unidades añadidas correctamente')
                            ->icon('heroicon-o-plus-circle')
                            ->success()
                            ->sendToDatabase($recipient)
                            ->send();
                }),

                Actions\Action::make('discountUnits')->label('Descontar')
                    ->modalHeading('Descontar unidades de un Producto')
                    ->modalIcon('heroicon-o-minus-circle')
                    ->color('gray')->icon('heroicon-o-minus-circle')
                    ->form([
                        Select::make('name')->label('Elige el producto')
                            ->required()
                            ->placeholder('Selecciona una opción')
                            ->options(Inventory::query()->where('user_id', Auth::user()->id)->pluck('name', 'id')),

                        TextInput::make('units_to_discount')->label('¿Cuántas unidades deseas descontar?')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                    ])
                    ->action(function (array $data): void {
                        $product = Inventory::findOrFail($data['name']);

                        if ($data['units_to_discount'] > $product->units) {
                            $recipient = auth()->user();
                            Notification::make()
                                ->title('Error: No hay suficientes unidades')
                                ->danger()
                                ->success()
                                ->sendToDatabase($recipient)
                                ->send();
                            return;
                        } else {
                            $product->units -= $data['units_to_discount'];
                            $product->save();

                            $recipient = auth()->user();
                            Notification::make()
                                ->title('Unidades descontadas correctamente')
                                ->icon('heroicon-o-minus-circle')
                                ->success()
                                ->sendToDatabase($recipient)
                                ->send();
                        }
                    }),
        ];

    }
}