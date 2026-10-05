<?php

namespace App\Filament\Resources\Usuarios\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UsuarioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // =========================
                // DATOS DEL USUARIO
                // =========================

                TextInput::make('Folio')
                    ->required(),

                TextInput::make('Nombre'),

                TextInput::make('IdTitulo')
                    ->numeric(),

                TextInput::make('CURP'),

                TextInput::make('Direccion'),

                TextInput::make('DireccionPart'),

                TextInput::make('Correo'),

                TextInput::make('CorreoAlterno'),

                TextInput::make('Celular'),

                TextInput::make('Telefono'),

                TextInput::make('IdBarra')
                    ->numeric(),

                TextInput::make('idEstatus')
                    ->numeric(),

                TextInput::make('Observaciones'),

                Select::make('idTipoPersona')
                    ->label('Tipo Persona')
                    ->relationship('tipoPersona', 'Descripcion')
                    ->searchable()
                    ->preload()
                    ->required(),

                DateTimePicker::make('FechaAlta')
                    ->label('Fecha alta')
                    ->native(false)
                    ->displayFormat('d/m/Y H:i'),

                TextInput::make('NoEmpleado')
                    ->numeric(),

                Toggle::make('CorreoVerificado')
                    ->label('Correo verificado'),

                TextInput::make('DireccionNoExt'),

                TextInput::make('DireccionNoInt'),

                TextInput::make('DireccionCP'),

                TextInput::make('DireccionColonia'),

                TextInput::make('DireccionMunicipio'),

                TextInput::make('DireccionEstado'),

                TextInput::make('DireccionPartNoExt'),

                TextInput::make('DireccionPartNoInt'),

                TextInput::make('DireccionPartCP'),

                TextInput::make('DireccionPartColonia'),

                TextInput::make('DireccionPartMunicipio'),

                TextInput::make('DireccionPartEstado'),

                Toggle::make('Activo')
                    ->label('Activo'),

                // =========================
                // PERFIL DE USUARIO
                // =========================

                Section::make('Perfil de usuario')
                    ->description('Configuración de acceso y seguridad del usuario')
                    ->relationship('userProfile')
                    ->schema([

                        TextInput::make('UserName')
                            ->label('Nombre de usuario')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('Password')
                            ->label('Contraseña')
                            ->password()
                            ->revealable()
                            ->required()
                            ->maxLength(255),

                        Toggle::make('IsActive')
                            ->label('Usuario activo')
                            ->required(),

                        Toggle::make('IsAdmin')
                            ->label('Administrador')
                            ->required(),

                        Toggle::make('IsRoot')
                            ->label('Root')
                            ->required(),

                        Toggle::make('IsApproved')
                            ->label('Usuario aprobado'),

                        Toggle::make('DigitalCert')
                            ->label('Segunda fase')
                            ->required(),

                        Toggle::make('Use2FAToken')
                            ->label('Usar 2FA')
                            ->required(),

                        DateTimePicker::make('CreationDate')
                            ->label('Fecha de creación')
                            ->native(false)
                            ->displayFormat('d/m/Y H:i')
                            ->seconds(false),
                            
                        TextInput::make('Token')
                            ->label('Token')
                            ->maxLength(255),

                        TextInput::make('PasswordQuestion')
                            ->label('Pregunta de contraseña')
                            ->maxLength(255),

                        TextInput::make('PasswordAnswer')
                            ->label('Respuesta de contraseña')
                            ->maxLength(255),

                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
