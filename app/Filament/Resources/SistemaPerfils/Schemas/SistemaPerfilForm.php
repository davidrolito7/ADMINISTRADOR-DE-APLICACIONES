<?php

namespace App\Filament\Resources\SistemaPerfils\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SistemaPerfilForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Información del Perfil')
                    ->columns(2)
                    ->schema([
                        Select::make('IdSistema')
                            ->label('Sistema')
                            ->relationship('sistema', 'NombreSis')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('Descripcion')
                            ->label('Descripción')
                            ->columnSpanFull()
                            ->required(),

                        Toggle::make('Activo')
                            ->label('Activo')
                            ->default(true),
                    ]),
            ]);
    }
}
