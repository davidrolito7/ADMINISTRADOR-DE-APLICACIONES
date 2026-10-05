<?php

namespace App\Filament\Resources\SistemaModulos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SistemaModulosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('IdSistemaModulo')
                    ->label('IdSistemaModulo')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('sistema.NombreSis')
                    ->label('Sistema')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('Nombre')
                    ->label('Módulo')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('Descripcion')
                    ->label('Descripción')
                    ->limit(40)
                    ->placeholder('—'),

                TextColumn::make('OrdenGrupoMenu')
                    ->label('Orden')
                    ->sortable(),

                TextColumn::make('pantallas_count')
                    ->label('Pantallas')
                    ->counts('pantallas')
                    ->badge()
                    ->color('info'),

                TextColumn::make('FechaImplementacion')
                    ->label('Implementación')
                    ->date('d/m/Y')
                    ->sortable()
                    ->placeholder('—'),

                IconColumn::make('VisibleMenu')
                    ->label('Menú')
                    ->boolean(),

                IconColumn::make('Activo')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->defaultSort('OrdenGrupoMenu')
            ->filters([
                SelectFilter::make('IdSistema')
                    ->label('Sistema')
                    ->relationship('sistema', 'NombreSis')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('VisibleMenu')
                    ->label('Visible en menú'),

                TernaryFilter::make('Activo')
                    ->label('Activo'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}