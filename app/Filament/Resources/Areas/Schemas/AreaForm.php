<?php

namespace App\Filament\Resources\Areas\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AreaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Section::make('Información general')
                    ->columns(2)
                    ->schema([
                        TextInput::make('Nombre')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),

                        Textarea::make('Descripcion')
                            ->label('Descripción')
                            ->rows(2)
                            ->columnSpan(2),

                        TextInput::make('Dirección')
                            ->label('Dirección')
                            ->maxLength(255)
                            ->columnSpan(2),

                        TextInput::make('Georeferencia')
                            ->label('Georeferencia')
                            ->maxLength(255),

                        Toggle::make('Activo')
                            ->label('Activo')
                            ->default(true)
                            ->required(),
                    ]),

                Section::make('Ubicación / Referencias')
                    ->columns(3)
                    ->schema([
                        TextInput::make('idMunicipio')
                            ->label('Municipio')
                            ->numeric(),

                        TextInput::make('IdRegion')
                            ->label('Región')
                            ->numeric(),

                        TextInput::make('IdDistrito')
                            ->label('Distrito')
                            ->numeric(),

                        TextInput::make('IdInstancia')
                            ->label('Instancia')
                            ->numeric(),

                        TextInput::make('IdAnterior')
                            ->label('Id anterior')
                            ->numeric(),
                    ]),

                Section::make('Contacto')
                    ->columns(2)
                    ->schema([
                        TextInput::make('NoEmpleadoResponsableArea')
                            ->label('No. empleado responsable'),

                        TextInput::make('TelefonoContacto')
                            ->label('Teléfono de contacto')
                            ->tel()
                            ->maxLength(50),

                        TextInput::make('ExtensionContacto')
                            ->label('Extensión de contacto')
                            ->maxLength(20),

                        TextInput::make('CorreoElectronicoContacto')
                            ->label('Correo electrónico de contacto')
                            ->email()
                            ->maxLength(255),

                        TextInput::make('Teléfono')
                            ->label('Teléfono')
                            ->tel()
                            ->maxLength(50),

                        TextInput::make('Correo')
                            ->label('Correo')
                            ->email()
                            ->maxLength(255),
                    ]),

                Section::make('Materias')
                    ->columns(4)
                    ->schema([
                        Toggle::make('Oficialia')->label('Oficialía'),
                        Toggle::make('Civil')->label('Civil'),
                        Toggle::make('Familiar')->label('Familiar'),
                        Toggle::make('Mercantil')->label('Mercantil'),
                        Toggle::make('Laboral')->label('Laboral'),
                        Toggle::make('Penal')->label('Penal'),
                        Toggle::make('PenalOral')->label('Penal oral'),
                        Toggle::make('Adolescentes')->label('Adolescentes'),
                        Toggle::make('Ejecución')->label('Ejecución'),
                        Toggle::make('Administrativa')->label('Administrativa'),
                        Toggle::make('Jurisdiccional')->label('Jurisdiccional'),
                        Toggle::make('Constitucional')->label('Constitucional'),
                        Toggle::make('Indigena')->label('Indígena'),
                    ]),

                Section::make('Observaciones')
                    ->schema([
                        Textarea::make('Observaciones')
                            ->label('Observaciones')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
