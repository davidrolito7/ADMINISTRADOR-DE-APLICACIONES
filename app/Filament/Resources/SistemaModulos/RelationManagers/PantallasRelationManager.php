<?php

namespace App\Filament\Resources\SistemaModulos\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PantallasRelationManager extends RelationManager
{
    protected static string $relationship = 'pantallas';
    protected static ?string $title = 'Pantallas';
    protected static ?string $modelLabel = 'Pantalla';
    protected static ?string $pluralModelLabel = 'Pantallas';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('Nombre')
                ->label('Nombre')
                ->required()
                ->maxLength(255),

            TextInput::make('Descripcion')
                ->label('Descripción')
                ->maxLength(500),

            TextInput::make('Ejecutable')
                ->label('Ejecutable')
                ->maxLength(255),

            TextInput::make('Parametros')
                ->label('Parámetros')
                ->maxLength(500),

            TextInput::make('Valores')
                ->label('Valores')
                ->maxLength(500),

            TextInput::make('Imagen')
                ->label('Imagen')
                ->maxLength(255),

            TextInput::make('Orden')
                ->label('Orden')
                ->numeric()
                ->default(0),

            TextInput::make('Acceso')
                ->label('Acceso')
                ->maxLength(255),

            DatePicker::make('FechaProduccion')
                ->label('Fecha de producción')
                ->native(false)
                ->displayFormat('d/m/Y')
                ->default(now()),

            Toggle::make('VisibleMenu')
                ->label('Visible en menú')
                ->default(true),

            Toggle::make('Activo')
                ->label('Activo')
                ->default(true),

            Repeater::make('secciones')
                ->relationship()
                ->label('Secciones')
                ->defaultItems(0)
                ->collapsed()
                ->itemLabel(fn(array $state): ?string => $state['Nombre'] ?? null)
                ->schema([
                    TextInput::make('Nombre')
                        ->label('Nombre de la sección')
                        ->required()
                        ->maxLength(255),

                    Textarea::make('Descripcion')
                        ->label('Descripción')
                        ->rows(2)
                        ->columnSpanFull(),

                    DatePicker::make('FechaAlta')
                        ->label('Fecha alta')
                        ->native(false)
                        ->displayFormat('d/m/Y')
                        ->default(now())
                        ->required(),

                    DatePicker::make('FechaBaja')
                        ->label('Fecha baja')
                        ->native(false)
                        ->displayFormat('d/m/Y'),

                    Toggle::make('Activo')
                        ->label('Activo')
                        ->default(true),
                ])
                ->columns(2)
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Nombre')
            ->columns([
                TextColumn::make('IdPantalla')
                    ->label('IdPantalla')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                    
                TextColumn::make('Orden')
                    ->label('Orden')
                    ->sortable(),

                TextColumn::make('Nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('Descripcion')
                    ->label('Descripción')
                    ->limit(40)
                    ->placeholder('—'),

                TextColumn::make('Ejecutable')
                    ->label('Ejecutable')
                    ->limit(30)
                    ->placeholder('—'),

                TextColumn::make('secciones_count')
                    ->label('Secciones')
                    ->counts('secciones')
                    ->badge(),

                TextColumn::make('FechaProduccion')
                    ->label('Producción')
                    ->date('d/m/Y')
                    ->placeholder('—'),

                IconColumn::make('VisibleMenu')
                    ->label('Menú')
                    ->boolean(),

                IconColumn::make('Activo')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->defaultSort('Orden')
            ->filters([
                TernaryFilter::make('VisibleMenu')->label('Visible en menú'),
                TernaryFilter::make('Activo')->label('Activo'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
