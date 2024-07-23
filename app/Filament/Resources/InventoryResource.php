<?php

namespace App\Filament\Resources;

use App\Models\User;
use App\Models\Role;
use App\Models\Condominium;
use App\Models\Inventory;
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
use Filament\Support\Enums\IconPosition;

class InventoryResource extends Resource
{
    protected static ?int $navigationSort = 5;
    protected static ?string $model = Inventory::class;
    protected static ?string $navigationGroup = 'Condominios';
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
                    ->required()->badge()
                    ->icon(function ($state) {
                        $state = (int)$state;
                        if ($state < 10) {
                            return 'heroicon-o-calculator'; }
                        elseif ($state < 100) { return 'heroicon-o-calculator'; }
                            return 'heroicon-o-user-circle';
                    }),

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
                    ->sortable()->searchable()->wrap()
                    ->description(fn (Inventory $record): string => $record->description),

                Tables\Columns\TextColumn::make('category')->label('Área')
                    ->searchable()->sortable()->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Mantenimiento' => 'info', 'Jardinería' => 'emerald', 'Iluminación' => 'warning',
                        'Limpieza' => 'emerald', 'Seguridad' => 'info', 'Suministros' => 'violet',
                        'Mobiliario' => 'violet', 'Tecnología' => 'cyan', 'Materiales' => 'cyan'})
                    ->icon(fn (string $state): string => match ($state) {
                        'Mantenimiento' => 'heroicon-o-key', 'Jardinería' => 'heroicon-o-sun', 'Iluminación' => 'heroicon-o-light-bulb',
                        'Limpieza' => 'heroicon-o-beaker', 'Seguridad' => 'heroicon-o-lock-closed', 'Suministros' => 'heroicon-o-inbox-stack',
                        'Mobiliario' => 'heroicon-o-cube', 'Tecnología' => 'heroicon-o-cpu-chip', 'Materiales' => 'heroicon-o-archive-box'}),

                Tables\Columns\TextColumn::make('units')->label('Unidades')
                    ->sortable()->searchable()->badge()->color('gray')->suffix('und')
                    ->icon(function (Inventory $record) {
                        if ($record->units < 20) { return 'heroicon-o-exclamation-triangle'; }
                    }),

                Tables\Columns\TextColumn::make('expiration')->label('Expiración')
                    ->sortable()->date()->placeholder('No expira'),

                Tables\Columns\TextColumn::make('created_at')->label('Añadido')
                    ->sortable()->since(),

                Tables\Columns\TextColumn::make('amount')->label('Costo')
                    ->sortable()->icon('heroicon-m-currency-dollar')
                    ->numeric(decimalPlaces: 2)->prefix('S/ ')->color('success'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label(''),
                Tables\Actions\DeleteAction::make()->label(''),
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
