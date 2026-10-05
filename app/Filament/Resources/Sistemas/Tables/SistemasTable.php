<?php

namespace App\Filament\Resources\Sistemas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SistemasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('IdSistema')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('NombreSis')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('Siglas')
                    ->label('Siglas')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('DescripcionSis')
                    ->label('Descripción')
                    ->limit(40)
                    ->placeholder('—'),

                TextColumn::make('areaSistemas_count')
                    ->label('Áreas')
                    ->counts('areaSistemas')
                    ->badge()
                    ->color('info'),

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
