<?php

namespace App\Filament\Resources;

use App\Models\User;
use App\Models\Condominium;
use App\Models\Inventory;
use App\Models\Category;
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
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CondominiumResource extends Resource
{
    protected static ?string $model = Condominium::class;
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
                    ->boolean(),
                Tables\Columns\TextColumn::make('name')->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')->label('Dirección')
                    ->searchable(),
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
            'index' => Pages\ListCondominium::route('/'),
            'create' => Pages\CreateCondominium::route('/create'),
            'edit' => Pages\EditCondominium::route('/{record}/edit'),
        ];
    }
}
