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
use App\Filament\Employee\Resources\ReportResource\Pages;
use App\Filament\Employee\Resources\ReportResource\RelationManagers;
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
use Filament\Tables\Columns\TextColumn;

class ReportResource extends Resource
{
    protected static ?int $navigationSort = 2;
    protected static ?string $model = Report::class;

    protected static ?string $slug = 'incidencias';
    protected static ?string $label = 'Incidencias';
    protected static ?string $navigationLabel = 'Incidencias';
    protected static ?string $navigationGroup = 'Condominio';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::user()->id);
    }

    
    public static function form(Form $form): Form
    {
        return $form

            ->schema([
                Forms\Components\TextInput::make('created_at')->label('Fecha')
                    ->required(),

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
            ->emptyStateHeading('No hay reportes registrados')->emptyStateIcon('heroicon-o-clipboard-document-check')
            ->emptyStateDescription('Cuando hayan reportes registrados, los verás aquí.')
            ->columns([
                    
                Tables\Columns\TextColumn::make('name')->label('Incidencia')
                    ->sortable()->searchable()->wrap()->description(
                        fn (Report $record): string => implode(' ', array_slice(str_word_count($record->description, 1), 0, 12)) . (str_word_count($record->description) > 12 ? '...' : '')
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

                Tables\Columns\TextColumn::make('condominium.name')->label('Condominio')
                    ->searchable()->sortable()->alignment(Alignment::Center),

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
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
        ];
    }
}
