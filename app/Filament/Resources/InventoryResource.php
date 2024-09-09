<?php

namespace App\Filament\Resources;

use App\Models\User;
use App\Models\Occupation;
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
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\IconPosition;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;


class InventoryResource extends Resource
{
    protected static ?int $navigationSort = 3;
    protected static ?string $model = Inventory::class;

    protected static ?string $slug = 'inventario';
    protected static ?string $label = 'Inventario';
    protected static ?string $navigationLabel = 'Inventario';
    protected static ?string $navigationGroup = 'Gestión de Condominios';
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

                Tables\Columns\TextColumn::make('name')->label('Producto')
                    ->sortable()->searchable()->wrap()->description(
                        fn (Inventory $record): string => implode(' ', array_slice(str_word_count($record->description, 1), 0, 16)) . (str_word_count($record->description) > 16 ? '...' : '')
                    ), //limita la descripción a 16 palabras como máximo

                Tables\Columns\TextColumn::make('category')->label('Categoría')
                    ->searchable()->sortable()->badge()->color('gray')->alignment(Alignment::Center)
                    ->icon(fn (string $state): string => match ($state) {
                        'Mantenimiento' => 'heroicon-o-key', 'Jardinería' => 'heroicon-o-sun', 'Iluminación' => 'heroicon-o-light-bulb',
                        'Limpieza' => 'heroicon-o-beaker', 'Seguridad' => 'heroicon-o-lock-closed', 'Suministros' => 'heroicon-o-inbox-stack',
                        'Mobiliario' => 'heroicon-o-cube', 'Tecnología' => 'heroicon-o-cpu-chip', 'Materiales' => 'heroicon-o-archive-box'}),

                Tables\Columns\TextColumn::make('units')->label('Unidades')
                    ->sortable()->searchable()->badge()->color('gray')->suffix(' und')->alignment(Alignment::Center)
                    ->icon(function (Inventory $record) {
                        if ($record->units < 20) { return 'heroicon-o-exclamation-triangle'; }
                    }),

                Tables\Columns\TextColumn::make('expiration')->label('Expiración')
                    ->sortable()->placeholder('No expira')
                    ->date('d-m-Y')->size(TextColumn\TextColumnSize::ExtraSmall),

                Tables\Columns\TextColumn::make('created_at')->label('Añadido')
                    ->sortable()->since()->size(TextColumn\TextColumnSize::ExtraSmall),

                Tables\Columns\TextColumn::make('amount')->label('Costo')
                    ->sortable()->icon('heroicon-m-currency-dollar')
                    ->numeric(decimalPlaces: 2)->prefix('S/ ')->color('success'),

            ])


            ->filters([
                //
            ])

            
            ->actions([
                Tables\Actions\ViewAction::make()->label(''),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()->label('Editar'),
                    Tables\Actions\DeleteAction::make()->label('Borrar'),
                ])->iconButton()->color('gray')->size('lg')->tooltip('Acciones')
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
