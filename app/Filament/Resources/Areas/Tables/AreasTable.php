<?php

namespace App\Filament\Resources\Areas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class AreasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('IdArea')
                    ->label('Id')
                    ->sortable(),

                TextColumn::make('Nombre')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('Descripcion')
                    ->label('Descripción')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('Dirección')
                    ->label('Dirección')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('TelefonoContacto')
                    ->label('Teléfono contacto')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('CorreoElectronicoContacto')
                    ->label('Correo contacto')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('Teléfono')
                    ->label('Teléfono')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('Correo')
                    ->label('Correo')
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('Oficialia')->label('Oficialía')->boolean()->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('Civil')->label('Civil')->boolean()->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('Familiar')->label('Familiar')->boolean()->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('Mercantil')->label('Mercantil')->boolean()->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('Laboral')->label('Laboral')->boolean()->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('Penal')->label('Penal')->boolean()->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('PenalOral')->label('Penal oral')->boolean()->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('Adolescentes')->label('Adolescentes')->boolean()->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('Ejecución')->label('Ejecución')->boolean()->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('Administrativa')->label('Administrativa')->boolean()->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('Jurisdiccional')->label('Jurisdiccional')->boolean()->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('Constitucional')->label('Constitucional')->boolean()->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('Indigena')->label('Indígena')->boolean()->toggleable(isToggledHiddenByDefault: true),

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
