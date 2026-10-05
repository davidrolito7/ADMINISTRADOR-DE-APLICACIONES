<?php

namespace App\Filament\Resources\AreaSistemas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AreaSistemaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Section::make('Información general')
                    ->columns(2)
                    ->schema([
                        Select::make('idSistema')
                            ->label('Sistema')
                            ->relationship('sistema', 'NombreSis')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('idArea')
                            ->label('Área')
                            ->relationship('area', 'Nombre')
                            ->searchable()
                            ->preload()
                            ->required(),

                        DatePicker::make('fechaImplementacion')
                            ->label('Fecha de implementación')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->required(),

                        DatePicker::make('fechaBaja')
                            ->label('Fecha de baja')
                            ->native(false)
                            ->displayFormat('d/m/Y'),

                        Toggle::make('Activo')
                            ->label('Activo')
                            ->default(true)
                            ->required(),
                    ]),
            ]);
    }
}
