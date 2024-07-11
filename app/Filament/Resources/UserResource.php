<?php

namespace App\Filament\Resources;

use App\Models\User;
use App\Models\Condominium;
use App\Models\Inventory;
use App\Models\Category;
use App\Models\Report;
use App\Models\Task;

use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Rinvex\Country\CountryLoader;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationLabel = 'Usuarios';
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    public static function getCountriesList()
    {
        $countries = countries();
        $countryList = [];

        foreach ($countries as $isoCode => $country) {
            $countryList[$isoCode] =  $country['name'];
        }

        return $countryList;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('Nombre')
                    ->required(),
                Forms\Components\TextInput::make('email')->label('Correo')
                    ->email()->required(),
                Forms\Components\TextInput::make('password')->label('Contraseña')
                    ->password()->required(),
                Forms\Components\TextInput::make('cellphone')->label('Teléfono')
                ->required(),
                Forms\Components\Select::make('condominium_id')->label('Condominio Asignado')
                    ->options(['1' => 'Los Pinos', '2' => 'Los Sauces', '3' => 'Los Altos',])
                    ->required(),
                Forms\Components\Select::make('country')->label('País')
                    ->options(function () { return self::getCountriesList(); })
                    ->required(),
                Forms\Components\Select::make('doc_type')->label('Tipo de Documento')
                    ->options(['DNI' => 'DNI - Documento Nacional de Identidad', 'CE' => 'CE - Carnet de Extranjería','PTP' => 'PTP - Permiso Temporal de Permanencia','PAS' => 'PAS - Pasaporte',])
                    ->required(),
                Forms\Components\TextInput::make('document')->label('Nro. de Documento')
                    ->required(),
                Forms\Components\TextInput::make('address')->label('Dirección')
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
                Tables\Columns\TextColumn::make('email')->label('Correo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cellphone')->label('Teléfono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('condominium.name')->label('Condominio')
                    ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('combined_column')->label('Documento')
                    ->getStateUsing(function ($record) { return
                        "{$record->doc_type} {$record->document}"; })
                    ->searchable()
                    ->sortable(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}