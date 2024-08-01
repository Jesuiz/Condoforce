<?php

namespace App\Filament\Resources;

use App\Models\User;
use App\Models\Role;
use App\Models\Condominium;
use App\Models\Inventory;
use App\Models\Report;
use App\Models\Task;

use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaskResource extends Resource
{
    protected static ?int $navigationSort = 3;
    protected static ?string $model = Task::class;
    protected static ?string $navigationGroup = 'Condominios';
    protected static ?string $navigationLabel = 'Actividades';
    protected static ?string $slug = 'actividades';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

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
            ->emptyStateHeading('No hay actividades registradas')->emptyStateIcon('heroicon-o-clipboard-document-list')
            ->emptyStateDescription('Cuando tengas tareas asignadas, las verás aquí.')
            ->columns([
                
                Tables\Columns\TextColumn::make('name')->label('Nombre')
                    ->searchable(),

                Tables\Columns\TextColumn::make('condominium.name')->label('Condominio')
                    ->searchable()->sortable(),

                Tables\Columns\TextColumn::make('area')->label('Área')
                    ->searchable()->sortable()->badge()
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
                    ->sortable()
                    ->tooltip(fn (string $state): string => match ($state) {
                        'Asignado' => 'Asignado', 'En Desarrollo' => 'En Desarrollo',
                        'Finalizado' => 'Finalizado', 'Fallido' => 'Fallido', default => 'Sin asignar'})
                    ->color(fn (string $state): string => match ($state) {
                        'Asignado' => 'warning', 'En Desarrollo' => 'info',
                        'Finalizado' => 'success', 'Fallido' => 'danger', default => 'gray'})
                    ->icon(fn (string $state): string => match ($state) {
                        'Asignado' => 'heroicon-o-exclamation-circle', 'En Desarrollo' => 'heroicon-o-ellipsis-horizontal-circle',
                        'Finalizado' => 'heroicon-o-check-circle', 'Fallido' => 'heroicon-o-x-circle', default => 'heroicon-o-question-mark-circle'}),

                Tables\Columns\TextColumn::make('time_limit')->label('Entrega')
                    ->searchable()->getStateUsing(function ($record) { return
                        "{$record->time_limit}/h"; }),

                Tables\Columns\TextColumn::make('description')->label('Descripción')
                    ->wrap()->lineClamp(3)->searchable(),

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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
