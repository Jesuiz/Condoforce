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
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;


class InventoryResource extends Resource
{
    protected static ?int $navigationSort = 5;
    protected static ?string $model = Inventory::class;

    protected static ?string $slug = 'inventario';
    protected static ?string $label = 'Productos en Inventario';
    protected static ?string $navigationLabel = 'Inventario';
    protected static ?string $navigationGroup = 'Condominios';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('name')->label('Nombre')
                    ->required(),

                Forms\Components\TextInput::make('description')->label('Descripción')
                    ->required(),
                    
                Forms\Components\Select::make('category')->label('Categoría')
                    ->required()->options([
                        'Mantenimiento' => 'Mantenimiento', 'Jardinería' => 'Jardinería', 'Iluminación' => 'Iluminación',
                        'Limpieza' => 'Limpieza', 'Seguridad' => 'Seguridad', 'Suministros' => 'Suministros',
                        'Mobiliario' => 'Mobiliario', 'Tecnología' => 'Tecnología', 'Materiales' => 'Materiales',
                    ])->placeholder('Selecciona una opción'),
                
                Forms\Components\TextInput::make('units')->label('Unidades')
                    ->required()->numeric()->minValue(1)->maxValue(999),

                Forms\Components\TextInput::make('amount')->label('Monto')
                    ->required()->numeric()->minValue(1)->maxValue(9999),

                Forms\Components\DatePicker::make('expiration')->label('Expiración')
                    ->required()->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('No hay productos registrados')->emptyStateIcon('heroicon-o-rectangle-stack')
            ->emptyStateDescription('Cuando tengas productos en el inventario, los verás aquí.')
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

    /* public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    } */
}
