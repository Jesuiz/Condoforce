<?php

namespace App\Filament\Resources;

use App\Models\User;
use App\Models\Role;
use App\Models\Condominium;
use App\Models\Inventory;
use App\Models\Report;
use App\Models\Task;

use Filament\Tables\Columns\ImageColumn;
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
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;

use Rinvex\Country\CountryLoader;

class UserResource extends Resource
{
    protected static ?int $navigationSort = 1;
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'Usuarios';
    protected static ?string $navigationLabel = 'Residentes';
    protected static ?string $navigationIcon = 'heroicon-o-users';

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

                Section::make('Sobre tu Información Personal')->columns(4)
                    ->description('Tus datos personales son importantes para validar tu relación al condominio')
                    ->schema([
                        Forms\Components\TextInput::make('name')->label('Nombre y Apellido')
                            ->required(),
                        Forms\Components\TextInput::make('email')->label('Correo')
                            ->email()->required(),
                        Forms\Components\TextInput::make('password')->label('Contraseña')
                            ->password()->required(),
                        Forms\Components\TextInput::make('cellphone')->label('Teléfono')
                            ->required(),
                        Forms\Components\Select::make('country')->label('País')
                            ->options(function () { return self::getCountriesList(); })
                            ->required(),
                        Forms\Components\Select::make('doc_type')->label('Tipo de Documento')
                            ->options(['DNI' => 'DNI - Documento Nacional de Identidad',
                            'CE' => 'CE - Carnet de Extranjería','PTP' => 'PTP - Permiso Temporal de Permanencia','PAS' => 'PAS - Pasaporte',])
                            ->required(),
                        Forms\Components\TextInput::make('document')->label('Nro. de Documento')
                            ->required(),
                        Forms\Components\TextInput::make('address')->label('Dirección')
                            ->required(),
                    ]),

                Section::make('Sobre el Condominio')->columns(2)
                    ->description('Los detalles sobre tu rol dentro del Condominio son esenciales para la seguridad de todos')
                    ->schema([
                        Forms\Components\Select::make('condominium_id')->label('Condominio Asignado')
                            ->relationship(name:'condominium', titleAttribute:'name')
                            ->preload()->live()->required(),
                        Forms\Components\Select::make('user.role')->label('Area')
                            ->options(['0' => 'Residente', '1' => 'Vigilante', '2' => 'Mantenimiento',
                            '3' => 'Supervisor', '4' => 'Delegado', '5' => 'Administrador', '6' => 'Gerente'])
                            ->required(),
                    ]),
            ]);
    }




    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                
                Tables\Columns\TextColumn::make('name')->label('Nombre')
                    ->searchable()->wrap()->description(
                        fn (User $record): string => "{$record->doc_type} {$record->document}"),

                Tables\Columns\TextColumn::make('role.name')->label('Tipo')
                    ->searchable()->badge()->color(
                        fn (string $state): string => match ($state) {
                        'Residente' => 'gray', 'Vigilante' => 'info',
                        'Mantenimiento' => 'info', 'Supervisor' => 'info',
                        'Delegado' => 'gray', 'Administrador' => 'rose', 'Gerente' => 'rose'})
                    ->icon(
                        fn (string $state): string => match ($state) {
                        'Residente' => 'heroicon-o-user-circle', 'Mantenimiento' => 'heroicon-o-wrench-screwdriver',
                        'Vigilante' => 'heroicon-o-video-camera', 'Supervisor' => 'heroicon-o-eye',
                        'Administrador' => 'heroicon-o-calculator', 'Gerente' => 'heroicon-o-star',
                        'Delegado' => 'heroicon-o-user-group'}),

                Tables\Columns\TextColumn::make('condominium.name')->label('Condominio')
                    ->searchable()->wrap()->description(
                        fn (User $record): string => "{$record->condominium->address}"),

                Tables\Columns\TextColumn::make('address')->label('Dirección'),

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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereIn('role_id', [1,2])->count();
    }
}
