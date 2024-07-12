<?php

namespace App\Filament\Resources;

use App\Models\User;
use App\Models\Condominium;
use App\Models\Inventory;
use App\Models\Category;
use App\Models\Report;
use App\Models\Task;

use App\Filament\Resources\InventoryResource\Pages;
use App\Filament\Resources\InventoryResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryResource extends Resource
{
    protected static ?int $navigationSort = 5;
    protected static ?string $model = Inventory::class;
    protected static ?string $navigationLabel = 'Inventario';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('Nombre')
                    ->required(),
                Forms\Components\TextInput::make('description')->label('Descripción')
                    ->required(),
                Forms\Components\TextInput::make('units')->label('Unidades')
                    ->required(),
                Forms\Components\TextInput::make('amount')->label('Monto')
                    ->required(),
                Forms\Components\TextInput::make('expiration')->label('Expiración')
                    ->required(),
                Forms\Components\TextInput::make('user_id')->label('Añadido por')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nombre')
                    ->wrap()->sortable()->searchable()->description(fn (Inventory $record): string => $record->description),
                Tables\Columns\TextColumn::make('units')->label('Und')
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('expiration')->label('Expiración')
                    ->sortable()->date(),
                Tables\Columns\TextColumn::make('created_at')->label('Añadido')
                    ->sortable()->since(),
                Tables\Columns\TextColumn::make('amount')->label('Monto')
                    ->sortable()->numeric(decimalPlaces: 2)->money('PEN')->color('success')->icon('heroicon-m-currency-dollar'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventories::route('/'),
            'create' => Pages\CreateInventory::route('/create'),
            'edit' => Pages\EditInventory::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
