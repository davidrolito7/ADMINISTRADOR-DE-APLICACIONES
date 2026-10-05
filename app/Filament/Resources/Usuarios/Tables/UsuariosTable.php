<?php

namespace App\Filament\Resources\Usuarios\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UsuariosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('IdGeneral')
                    ->label('IdGeneral')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('NoEmpleado')
                    ->searchable(),

                TextColumn::make('Folio')
                    ->searchable(),

                TextColumn::make('Nombre')
                    ->searchable(),

                TextColumn::make('CURP')
                    // ->searchable()
                    ->toggleable(),

                TextColumn::make('Correo')
                    // ->searchable()
                    ->toggleable(),

                TextColumn::make('Celular')
                    ->toggleable(),

                TextColumn::make('tipoPersona.Descripcion')
                    ->label('Tipo Persona')
                    ->toggleable(),

                IconColumn::make('userProfile.DigitalCert')
                    ->label('Segunda Fase')
                    ->boolean()
                    ->toggleable(),
                TextColumn::make('FechaAlta')
                    ->dateTime('d/m/Y')
                    ->toggleable(),

                IconColumn::make('CorreoVerificado')
                    ->boolean()
                    ->toggleable(),

                IconColumn::make('Activo')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('Activo'),

                TernaryFilter::make('CorreoVerificado')
                    ->label('Correo Verificado'),

                SelectFilter::make('idTipoPersona')
                    ->label('Tipo Persona')
                    ->relationship('tipoPersona', 'Descripcion'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('Folio', 'asc');
    }
}
