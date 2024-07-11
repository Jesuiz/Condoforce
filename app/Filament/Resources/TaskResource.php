<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;
    protected static ?string $navigationLabel = 'Actividades';
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
            ->columns([
                Tables\Columns\IconColumn::make('status')->label('Status')
                ->color(fn (string $state): string => match ($state) {
                    'Asignado' => 'warning', 'En Desarrollo' => 'info',
                    'Finalizado' => 'success', 'Fallido' => 'danger',
                    default => 'gray'})
                ->icon(fn (string $state): string => match ($state) {
                    'Asignado' => 'heroicon-o-exclamation-circle', 'En Desarrollo' => 'heroicon-o-ellipsis-horizontal-circle',
                    'Finalizado' => 'heroicon-o-check-circle', 'Fallido' => 'heroicon-o-x-circle',
                    default => 'gray',
                }),
                Tables\Columns\TextColumn::make('name')->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('condominium.name')->label('Condominio')
                    ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('area')->label('Área')
                    ->searchable()
                    ->icon(fn (string $state): string => match ($state) {
                        'Residente' => 'heroicon-o-user-circle', 'Mantenimiento' => 'heroicon-o-wrench-screwdriver',
                        'Vigilancia' => 'heroicon-o-video-camera', 'Supervisión' => 'heroicon-o-eye',
                        'Administración' => 'heroicon-o-calculator', 'Gerencia' => 'heroicon-o-star',
                        'Delegación' => 'heroicon-o-user-group', default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('time_limit')->label('Entrega')
                    ->searchable()->getStateUsing(function ($record) { return
                        "{$record->time_limit}/h"; }),
                Tables\Columns\TextColumn::make('description')->label('Descripción')
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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
