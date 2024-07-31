<?php

namespace App\Filament\Employee\Resources;

use App\Models\User;
use App\Models\Role;
use App\Models\Condominium;
use App\Models\Inventory;
use App\Models\Report;
use App\Models\Task;

use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Employee\Resources\InventoryResource\Pages;
use App\Filament\Employee\Resources\InventoryResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Support\Enums\ActionSize;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\HtmlString;


class InventoryResource extends Resource
{
    protected static ?int $navigationSort = 3;
    protected static ?string $model = Inventory::class;
    
    protected static ?string $slug = 'inventario';
    protected static ?string $label = 'Productos';
    protected static ?string $navigationLabel = 'Inventario';
    protected static ?string $navigationGroup = 'Condominio';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::user()->id);
    }
    

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
                    ->sortable()->searchable()->wrap()
                    ->description(fn (Inventory $record): string => $record->description),

                Tables\Columns\TextColumn::make('category')->label('Área')
                    ->searchable()->sortable()->badge()->alignment(Alignment::Center)
                    ->color(fn (string $state): string => match ($state) {
                        'Mantenimiento' => 'info', 'Jardinería' => 'emerald', 'Iluminación' => 'warning',
                        'Limpieza' => 'emerald', 'Seguridad' => 'info', 'Suministros' => 'violet',
                        'Mobiliario' => 'violet', 'Tecnología' => 'cyan', 'Materiales' => 'cyan'})
                    ->icon(fn (string $state): string => match ($state) {
                        'Mantenimiento' => 'heroicon-o-key', 'Jardinería' => 'heroicon-o-sun', 'Iluminación' => 'heroicon-o-light-bulb',
                        'Limpieza' => 'heroicon-o-beaker', 'Seguridad' => 'heroicon-o-lock-closed', 'Suministros' => 'heroicon-o-inbox-stack',
                        'Mobiliario' => 'heroicon-o-cube', 'Tecnología' => 'heroicon-o-cpu-chip', 'Materiales' => 'heroicon-o-archive-box'}),

                Tables\Columns\TextColumn::make('units')->label('Unidades')
                    ->sortable()->searchable()->badge()->color('gray')->suffix(' und')
                    ->alignment(Alignment::Center)->icon(function (Inventory $record) {
                        if ($record->units < 20) { return 'heroicon-o-exclamation-triangle'; }
                    }),

                Tables\Columns\TextColumn::make('created_at')->label('Comprado')
                    ->sortable()->date('d-m-Y')->size(TextColumn\TextColumnSize::ExtraSmall)->alignment(Alignment::Center)
                    ->description(function (Inventory $record): HtmlString {
                        if ($record->expiration) {
                            return new HtmlString("<span class='text-xs text-gray-400'>Expira el {$record->expiration}</span>");
                        }   return new HtmlString("<span class='text-xs text-gray-400'>No expira</span>");
                    }),

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
}
