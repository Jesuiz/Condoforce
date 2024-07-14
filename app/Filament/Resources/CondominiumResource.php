<?php

namespace App\Filament\Resources;

use App\Models\User;
use App\Models\Role;
use App\Models\Condominium;
use App\Models\Inventory;
use App\Models\Report;
use App\Models\Task;

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

class CondominiumResource extends Resource
{
    protected static ?int $navigationSort = 2;
    protected static ?string $model = Condominium::class;

    protected static ?string $modelLabel = 'condominios';
    protected static ?string $navigationGroup = 'Condominios';
    protected static ?string $navigationLabel = 'Condominios';
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('Nombre')
                    ->required(),
                Forms\Components\TextInput::make('address')->label('Dirección')
                    ->required(),
                Forms\Components\TextInput::make('is_active')->label('Status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\IconColumn::make('is_active')->label('Status')
                    ->boolean()->sortable(),

                Tables\Columns\TextColumn::make('name')->label('Condominio')
                    ->searchable()->wrap()->description(
                        fn (Condominium $record): string => "{$record->address}")
                    /* ->url(fn (Condominium $record): string => route('condominium.details', ['condominium' => $record])) */,

                Tables\Columns\ImageColumn::make('user.name')->label('Empleados')
                    ->circular()->stacked()->limit(2)
                    ->limitedRemainingText()->placeholder('Sin empleados'),

                Tables\Columns\TextColumn::make('budget')->label('Presupuesto')
                    ->numeric(decimalPlaces:0)->prefix('S/ ')->color('success')
                    ->icon('heroicon-m-currency-dollar')/* ->visible(auth()->user()->isAdmin()) */,

                /* Tables\Columns\TextColumn::make('expenses')->label('Gastos')
                    ->numeric(decimalPlaces:0)->prefix('S/ ')->color('rose')
                    ->icon('heroicon-m-currency-dollar')/* ->visible(auth()->user()->isAdmin()) */

                Tables\Columns\TextColumn::make('inventory.name')->label('Inventario')
                    ->sortable()->searchable()->wrap()->badge()->color('gray')
                    ->wrap()->placeholder('Sin productos en stock')

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                    
                Tables\Actions\DeleteAction::make(),
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

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
