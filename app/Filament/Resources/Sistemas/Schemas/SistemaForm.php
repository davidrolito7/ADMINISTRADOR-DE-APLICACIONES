<?php

namespace App\Filament\Resources\Sistemas\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SistemaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Section::make('Información general')
                    ->columns(2)
                    ->schema([
                        TextInput::make('NombreSis')
                            ->label('Nombre del sistema')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('Siglas')
                            ->label('Siglas')
                            ->maxLength(50),

                        Textarea::make('DescripcionSis')
                            ->label('Descripción')
                            ->columnSpanFull(),

                        TextInput::make('DirectorioEnServidor')
                            ->label('Directorio en servidor')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Toggle::make('Activo')
                            ->label('Activo')
                            ->default(true),
                    ]),
            ]);
    }
}
