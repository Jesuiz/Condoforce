<?php

namespace App\Filament\Resident\Resources;

use App\Models\User;
use App\Models\Occupation;
use App\Models\Condominium;
use App\Models\Inventory;
use App\Models\Report;
use App\Models\Task;

use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resident\Resources\ReportResource\Pages;
use App\Filament\Resident\Resources\ReportResource\RelationManagers;
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
use Filament\Tables\Columns\TextColumn;

class ReportResource extends Resource
{
    protected static ?int $navigationSort = 2;
    protected static ?string $model = Report::class;

    protected static ?string $slug = 'incidencias';
    protected static ?string $label = 'Incidencias';
    protected static ?string $navigationLabel = 'Incidencias';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::user()->id);
    }

    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Sobre el Reporte')->columns(3)
                    ->description('Los detalles son importantes para establecer un protocolo de acción')
                    ->schema([

                        Forms\Components\TextInput::make('name')->label('Nombre de la Incidencia')
                            ->required(),

                        Forms\Components\Select::make('area')->label('Área')
                            ->options(['Residente' => 'Residente', 'Delegación' => 'Delegación',
                            'Vigilancia' => 'Vigilancia', 'Mantenimiento' => 'Mantenimiento',
                            'Supervisión' => 'Supervisión', 'Gerencia' => 'Gerencia',])
                            ->required()->placeholder('Selecciona una opción'),

                        Forms\Components\DateTimePicker::make('created_at')->label('Fecha')
                            ->required()->date('d-m-Y'),

                        ]),

                Section::make('Sobre la Incidencia')->columns(1)
                    ->description('Comenta todo lo relevante acerca a la incidencia ocurrida con sus detalles')
                    ->schema([

                        Forms\Components\TextInput::make('description')->label('Descripción de la Incidencia')
                            ->required()->maxLength(1500),

                        ]),
                        
                        Forms\Components\Hidden::make('user_id')
                            ->default(fn () => Auth::user()->id)
                            ->required(),
                        Forms\Components\Hidden::make('condominium_id')
                            ->default(fn () => Auth::user()->condominium_id)
                            ->required(),
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('No hay reportes registrados')->emptyStateIcon('heroicon-o-clipboard-document-check')
            ->emptyStateDescription('Cuando hayan reportes registrados, los verás aquí.')
            ->columns([
                    
                Tables\Columns\TextColumn::make('created_at')->label('Fecha')
                    ->sortable()->date('d-m-Y')->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->alignment(Alignment::Center),

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

                Tables\Columns\TextColumn::make('name')->label('Incidencia')
                    ->sortable()->searchable()->wrap()
                    ->description(fn (Report $record): string => $record->description),

                    //TODO: OPTIMIZAR RESPONSIVE DEL TABLE COLUMN DESCRIPTION
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
                ])


            ->actions([
                Tables\Actions\ViewAction::make()->label(''),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ExportAction::make()->label('Descargar'),
                    Tables\Actions\EditAction::make()->label('Editar'),
                    Tables\Actions\DeleteAction::make()->label('Borrar'),
                ])->iconButton()->color('gray')->size('lg')->tooltip('Acciones')
            ])


            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\ExportBulkAction::make()->label('Descargar Seleccionados'),
                    Tables\Actions\DeleteBulkAction::make()->label('Borrar Seleccionados'),
                ])->label('Acciones Masivas'),
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
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
        ];
    }
}
