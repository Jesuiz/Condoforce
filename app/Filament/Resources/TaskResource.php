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
use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
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
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Get;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class TaskResource extends Resource
{
    protected static ?int $navigationSort = 1;
    protected static ?string $model = Task::class;
    
    protected static ?string $slug = 'actividades';
    protected static ?string $label = 'Actividades';
    protected static ?string $navigationLabel = 'Actividades';
    protected static ?string $navigationGroup = 'Gestión de Condominios';
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
                
                Tables\Columns\TextColumn::make('name')->label('Actividad')
                    ->sortable()->searchable()->wrap()->description(
                        fn (Task $record): string => implode(' ', array_slice(str_word_count($record->description, 1), 0, 12)) . (str_word_count($record->description) > 12 ? '...' : '')
                    ), //limita la descripción a 12 palabras como máximo

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

                Tables\Columns\TextColumn::make('status')->label('Status')
                    ->alignment(Alignment::Center)->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->icon(fn (string $state): string => match ($state) {
                        'Asignado' => 'heroicon-o-exclamation-circle', 'En Desarrollo' => 'heroicon-o-ellipsis-horizontal-circle',
                        'Finalizado' => 'heroicon-o-check-circle', 'Fallido' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle'})
                    ->color(fn (string $state): string => match ($state) {
                        'Asignado' => 'warning', 'En Desarrollo' => 'info',
                        'Finalizado' => 'success', 'Fallido' => 'danger', default => 'gray'}),
                //TODO: Las tareas 'completadas' y 'fallidas' deben deshabilitar la edicion para empleados. Las fallidas deben pedir una causa.

                Tables\Columns\TextColumn::make('time_limit')->label('Límite')
                    ->suffix('/h')->alignment(Alignment::Center)->placeholder('Ninguno'),

                Tables\Columns\TextColumn::make('created_at')->label('Fecha')
                    ->sortable()->date('d-m-Y')->size(TextColumn\TextColumnSize::ExtraSmall)->alignment(Alignment::Center),
                    
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

/*     public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    } */
}
