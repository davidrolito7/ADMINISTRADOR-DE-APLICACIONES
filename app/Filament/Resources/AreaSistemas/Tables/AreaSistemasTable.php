<?php

namespace App\Filament\Resources\AreaSistemas\Tables;

use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class AreaSistemasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('idAreaSistema')
                    ->label('idAreaSistema')
                    ->sortable(),

                TextColumn::make('idSistema')
                    ->label('idSistema')
                    ->sortable(),   
                TextColumn::make('sistema.NombreSis')
                    ->label('Sistema')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('idArea')
                    ->label('idArea')
                    ->sortable(),
                TextColumn::make('area.Nombre')
                    ->label('Área')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('fechaImplementacion')
                    ->label('Implementación')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('fechaBaja')
                    ->label('Baja')
                    ->date('d/m/Y')
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('usuarios_count')
                    ->label('Usuarios')
                    ->counts('usuarios')
                    ->badge()
                    ->color('info'),

                IconColumn::make('Activo')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('idSistema')
                    ->label('Sistema')
                    ->searchable()
                    ->relationship('sistema', 'NombreSis'),

                SelectFilter::make('idArea')
                    ->label('Área')
                    ->searchable()
                    ->relationship('area', 'Nombre'),

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
