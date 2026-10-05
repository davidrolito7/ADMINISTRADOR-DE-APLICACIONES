<?php

namespace App\Filament\Resources\SistemaModulos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SistemaModuloForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Información del Módulo')
                    ->columns(2)
                    ->schema([
                        Select::make('IdSistema')
                            ->label('Sistema')
                            ->relationship('sistema', 'NombreSis')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('Nombre')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('Descripcion')
                            ->label('Descripción')
                            ->required()
                            ->maxLength(500),

                        TextInput::make('OrdenGrupoMenu')
                            ->label('Orden en menú')
                            ->numeric()
                            ->default(0),

                        DatePicker::make('FechaImplementacion')
                            ->label('Fecha de implementación')
                            ->native(false)
                            ->required()
                            ->displayFormat('d/m/Y')
                            ->default(now()),


                        DatePicker::make('FechaBaja')
                            ->label('Fecha de baja')
                            ->native(false)
                            ->displayFormat('d/m/Y'),

                        Toggle::make('VisibleMenu')
                            ->label('Visible en menú')
                            ->default(true),

                        Toggle::make('Activo')
                            ->label('Activo')
                            ->default(true),
                    ]),
            ]);
    }
}
