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
use App\Filament\Employee\Resources\TaskResource\Pages;
use App\Filament\Employee\Resources\TaskResource\RelationManagers;
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
use Filament\Forms\Get;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;


class TaskResource extends Resource
{
    protected static ?int $navigationSort = 1;
    protected static ?string $model = Task::class;

    protected static ?string $slug = 'actividades';
    protected static ?string $label = 'Actividades';
    protected static ?string $navigationLabel = 'Actividades';
    protected static ?string $navigationGroup = 'Condominio';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

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

                Forms\Components\TextInput::make('area')->label('Área')
                    ->required(),
                    
                Forms\Components\TextInput::make('status')->label('Status')
                    ->required(),
                
                Forms\Components\TextInput::make('time_limit')->label('Límite de Tiempo')
                    ->required(),

                Forms\Components\TextInput::make('user_id')->label('Empleado designado')
                    ->required(),
                
                Forms\Components\TextInput::make('condominium_id')->label('Condominio del empleado')
                    ->required(),
/* 
                Forms\Components\Radio::make('asiggment')->label('¿Desea asignarlo a un reporte?')
                    ->boolean()->live(), */

                Forms\Components\CheckboxList::make('asiggment')->label('¿Desea asignarlo a un reporte?')
                    ->live()->options([
                        'asiggment_false' => 'Sin asignar',
                        'asiggment_true' => 'Asignar reporte', ])
                        ->descriptions([
                            'asiggment_false' => 'Marca esta opción si no es necesario relacionar esta tarea a un reporte',
                            'asiggment_true' => 'Esta actividad se relacionará con un reporte ya creado',
                        ]),

                Forms\Components\Select::make('report_id')->label('Selecciona el reporte')
                    ->searchable()->options(Report::all()->pluck('name', 'id'))
                    ->visible(fn (Get $get): bool => $get('asiggment')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                
                Tables\Columns\TextColumn::make('name')->label('Nombre')
                    ->sortable()->searchable()->wrap()
                    ->description(fn (Task $record): string => $record->description),

                Tables\Columns\TextColumn::make('area')->label('Área')
                    ->searchable()->sortable()->badge()->alignment(Alignment::Center)
                    ->color(fn (string $state): string => match ($state) {
                        'Residente' => 'gray', 'Vigilancia' => 'info',
                        'Mantenimiento' => 'info', 'Supervisión' => 'info',
                        'Delegación' => 'gray', 'Administración' => 'rose', 'Gerencia' => 'rose'})
                    ->icon(fn (string $state): string => match ($state) {
                        'Residente' => 'heroicon-o-user-circle', 'Mantenimiento' => 'heroicon-o-wrench-screwdriver',
                        'Vigilancia' => 'heroicon-o-video-camera', 'Supervisión' => 'heroicon-o-eye',
                        'Administración' => 'heroicon-o-calculator', 'Gerencia' => 'heroicon-o-star',
                        'Delegación' => 'heroicon-o-user-group'}),

                Tables\Columns\IconColumn::make('status')->label('Status')
                    ->alignment(Alignment::Center)
                    ->tooltip(fn (string $state): string => match ($state) {
                        'Asignado' => 'Asignado', 'En Desarrollo' => 'En Desarrollo',
                        'Finalizado' => 'Finalizado', 'Fallido' => 'Fallido', default => 'Sin asignar'})
                    ->color(fn (string $state): string => match ($state) {
                        'Asignado' => 'warning', 'En Desarrollo' => 'info',
                        'Finalizado' => 'success', 'Fallido' => 'danger', default => 'gray'})
                    ->icon(fn (string $state): string => match ($state) {
                        'Asignado' => 'heroicon-o-exclamation-circle', 'En Desarrollo' => 'heroicon-o-ellipsis-horizontal-circle',
                        'Finalizado' => 'heroicon-o-check-circle', 'Fallido' => 'heroicon-o-x-circle', default => 'heroicon-o-question-mark-circle'}),

                Tables\Columns\TextColumn::make('time_limit')->label('Límite')
                    ->searchable()->suffix('/h')->alignment(Alignment::Center)->placeholder('Ninguno'),

                Tables\Columns\CheckboxColumn::make('finish')->label('Finalizado')
                    ->searchable()->alignment(Alignment::Center),
                    //TODO: Al finalizar una tarea, debe cambiar el time_limit a 'ninguno',deshabilitarse el checkbox y archivarse en 'completadas'.
            ])


            ->filters([
                SelectFilter::make('condominium')->label('Condominio')
                    ->relationship('condominium', 'name'),
                    
                SelectFilter::make('area')->label('Área')
                    ->options([
                        '1' => 'Residente',
                        '2' => 'Delegación',
                        '3' => 'Vigilancia',
                        '4' => 'Mantenimiento',
                        '5' => 'Supervisión',
                        '6' => 'Administración',
                        '7' => 'Gerencia' ]),

                SelectFilter::make('status')->label('Status')
                    ->options([
                    'Asignado' => 'Asignado',
                        'En Desarrollo' => 'En Desarrollo',
                        'Finalizado' => 'Finalizado',
                        'Fallido' => 'Fallido' ])
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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
