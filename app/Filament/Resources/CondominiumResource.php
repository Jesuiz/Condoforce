<?php

namespace App\Filament\Resources;

use App\Models\User;
use App\Models\Occupation;
use App\Models\Condominium;
use App\Models\Inventory;
use App\Models\Report;
use App\Models\Task;

use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\CondominiumResource\Pages;
use App\Filament\Resources\CondominiumResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\Alignment;

class CondominiumResource extends Resource
{
    protected static ?int $navigationSort = 4;
    protected static ?string $model = Condominium::class;

    protected static ?string $slug = 'condominios';
    protected static ?string $label = 'Condominios';
    protected static ?string $navigationLabel = 'Condominios';
    protected static ?string $navigationGroup = 'Gestión de Condominios';
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Sobre el nuevo Condominio')->columns(3)
                    ->description('Introduce los detalles del nuevo Condominio')
                    ->schema([

                        Forms\Components\TextInput::make('name')->label('Nombre')
                            ->required(),

                        Forms\Components\TextInput::make('address')->label('Dirección')
                            ->required(),
                            
                        Forms\Components\TextInput::make('budget')->label('Presupuesto')
                            ->required()->prefix('S/'),
                    ]),
                        
/*                 Section::make('Sobre los usuarios  del nuevo Condominio')->columns(3)
                    ->description('Introduce los detalles de los usuarios asignados al condominio')
                    ->schema([

                        Forms\Components\TextInput::make('name')->label('Nombre')
                            ->required(),

                        Forms\Components\TextInput::make('address')->label('Dirección')
                            ->required(),
                            
                        Forms\Components\TextInput::make('budget')->label('Presupuesto')
                            ->required(),
                    ]), */

                    //TODO: PLANIFICAR ASIGNACION DE USUARIOS, TAREAS, PRODUCTOS Y REPORTES AL NUEVO CONDOMINIO
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('No hay condominios registrados')->emptyStateIcon('heroicon-o-users')
            ->emptyStateDescription('Cuando haya condominios registrados, los verás aquí.')
            ->columns([

                Tables\Columns\IconColumn::make('is_active')->label('Status')
                    ->boolean()->sortable()->alignment(Alignment::Center),

                Tables\Columns\TextColumn::make('name')->label('Condominio')
                    ->searchable()->wrap()->description(
                        fn (Condominium $record): string => "{$record->address}"),
                    
                Tables\Columns\ImageColumn::make('user.profile_img')->label('')
                    ->circular()->stacked()->limit(2)->alignment(Alignment::Center)
                    ->limitedRemainingText()->placeholder('Sin empleados'),

                Tables\Columns\TextColumn::make('budget')->label('Presupuesto')
                    ->numeric(decimalPlaces:0)->prefix('S/ ')->color('success')
                    ->icon('heroicon-m-currency-dollar')/* ->visible(auth()->user()->isAdmin()) */,

                /* Tables\Columns\TextColumn::make('expenses')->label('Gastos')
                    ->numeric(decimalPlaces:0)->prefix('S/ ')->color('rose')
                    ->icon('heroicon-m-currency-dollar')/* ->visible(auth()->user()->isAdmin()) */

                Tables\Columns\TextColumn::make('inventory.name')->label('Inventario')
                    ->sortable()->searchable()->wrap()->badge()->color('gray')->limitList(3)
                    ->wrap()->placeholder('Sin productos en stock')

            ])


            ->filters([
                SelectFilter::make('is_active')->label('Status')
                ->options([
                    '0' => 'Inactivo',
                    '1' => 'Activo', ]),
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
            'index' => Pages\ListCondominium::route('/'),
            'create' => Pages\CreateCondominium::route('/create'),
            'edit' => Pages\EditCondominium::route('/{record}/edit'),
        ];
    }

    /* public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    } */
}
