<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Filament\Resources\ReportResource\RelationManagers;
use App\Models\Report;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;
    protected static ?string $navigationLabel = 'Incidencias';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

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
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->label('Fecha')
                ->dateTime(),
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

                Tables\Columns\TextColumn::make('name')->label('Incidencia')
                    ->searchable(),
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
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
        ];
    }
}
