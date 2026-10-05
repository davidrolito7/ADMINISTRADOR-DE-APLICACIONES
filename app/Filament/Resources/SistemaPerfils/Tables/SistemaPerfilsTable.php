<?php

namespace App\Filament\Resources\SistemaPerfils\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SistemaPerfilsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('IdSistemaPerfil')
                    ->label('IdSistemaPerfil')
                    ->sortable(),

                TextColumn::make('sistema.NombreSis')
                    ->label('Sistema')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('Descripcion')
                    ->label('Descripción')
                    ->sortable()
                    ->searchable(),

                IconColumn::make('Activo')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->filters([
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
