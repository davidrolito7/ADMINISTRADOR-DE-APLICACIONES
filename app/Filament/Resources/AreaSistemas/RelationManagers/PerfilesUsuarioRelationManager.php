<?php

namespace App\Filament\Resources\AreaSistemas\RelationManagers;

use App\Models\ADM\AreaSistemaUsuario;
use App\Models\ADM\SistemaPerfil;
use App\Models\ADM\SistemaPerfilUsuario;
use App\Models\Usuario;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class PerfilesUsuarioRelationManager extends RelationManager
{
    protected static string $relationship = 'usuarioPerfiles';
    protected static ?string $title = 'Perfiles asignados';
    protected static ?string $modelLabel = 'Perfil';
    protected static ?string $pluralModelLabel = 'Perfiles';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('IdAreaSistemaUsuario')
                ->label('Usuario')
                //->searchable()
                ->getSearchResultsUsing(fn (string $search): array => $this->searchUsuariosAreaSistema($search))
                ->getOptionLabelUsing(fn ($value): ?string => $this->getNombreUsuarioAreaSistema($value))
                ->required(),

            Select::make('IdSistemaPerfil')
                ->label('Perfil')
                ->options(function (): array {
                    $idSistema = $this->getOwnerRecord()->idSistema;

                    return SistemaPerfil::where('Activo', true)
                        ->where('IdSistema', $idSistema)
                        ->pluck('Descripcion', 'IdSistemaPerfil')
                        ->all();
                })
              // ->searchable()
                ->required(),

            DateTimePicker::make('FechaAlta')
                ->label('Fecha alta')
                ->native(false)
                ->displayFormat('d/m/Y H:i')
                ->default(now())
                ->required(),

            DateTimePicker::make('FechaBaja')
                ->label('Fecha baja')
                ->native(false)
                ->displayFormat('d/m/Y H:i'),

            Toggle::make('Activo')
                ->label('Activo')
                ->default(true),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('IdUsuarioPerfil')
            // Evita que la carga de usuario genere un WHERE IN de más de 2100 IDs.
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(25)
            ->columns([
                TextColumn::make('IdUsuarioPerfil')
                    ->label('IdUsuarioPerfil')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('areaSistemaUsuario.usuario.Nombre')
                    ->label('Usuario')
                   // ->searchable()
                    ->sortable(),

                TextColumn::make('areaSistemaUsuario.cargo.Descripcion')
                    ->label('Cargo')
                    ->sortable(),

                TextColumn::make('sistemaPerfil.Descripcion')
                    ->label('Perfil')
                    ->badge(),

                TextColumn::make('FechaAlta')
                    ->label('Fecha alta')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('FechaBaja')
                    ->label('Fecha baja')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('-'),

                IconColumn::make('Activo')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('Activo')
                    ->label('Activo')
                    ->queries(
                        true: fn($query) => $query->where('ADM_SISTEMA_PERFILES_USUARIOS.Activo', true),
                        false: fn($query) => $query->where('ADM_SISTEMA_PERFILES_USUARIOS.Activo', false),
                    ),

                SelectFilter::make('IdSistemaPerfil')
                    ->label('Perfil')
                    ->options(function (): array {
                        $idSistema = $this->getOwnerRecord()->idSistema;

                        return SistemaPerfil::where('Activo', true)
                            ->where('IdSistema', $idSistema)
                            ->pluck('Descripcion', 'IdSistemaPerfil')
                            ->all();
                    }),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Asignar')
                    ->databaseTransaction()
                    ->using(function (array $data): SistemaPerfilUsuario {
                        $record = new SistemaPerfilUsuario();
                        $record->fill($data);
                        $record->save();

                        return $record;
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->databaseTransaction()
                    ->fillForm(fn(SistemaPerfilUsuario $record): array => $record->attributesToArray())
                    ->using(function (array $data, $livewire, SistemaPerfilUsuario $record): void {
                        $record->update($data);
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Busca usuarios sin crear un WHERE IN con todos los usuarios del área.
     */
    protected function searchUsuariosAreaSistema(string $search): array
    {
        $idAreaSistema = $this->getOwnerRecord()->idAreaSistema;

        $usuariosEncontrados = Usuario::query()
            ->where('Nombre', 'like', "%{$search}%")
            ->orderBy('Nombre')
            ->limit(50)
            ->pluck('Nombre', 'IdGeneral');

        if ($usuariosEncontrados->isEmpty()) {
            return [];
        }

        $usuariosArea = AreaSistemaUsuario::query()
            ->where('IdAreaSistema', $idAreaSistema)
            ->whereIn('IdGeneral', $usuariosEncontrados->keys()->all())
            ->get(['IdAreaSistemaUsuario', 'IdGeneral']);

        return $usuariosArea
            ->mapWithKeys(function (AreaSistemaUsuario $usuarioArea) use ($usuariosEncontrados): array {
                $nombre = $usuariosEncontrados[$usuarioArea->IdGeneral] ?? "Usuario #{$usuarioArea->IdGeneral}";

                return [$usuarioArea->IdAreaSistemaUsuario => $nombre];
            })
            ->all();
    }

    protected function getNombreUsuarioAreaSistema($idAreaSistemaUsuario): ?string
    {
        $idGeneral = AreaSistemaUsuario::query()
            ->whereKey($idAreaSistemaUsuario)
            ->value('IdGeneral');

        return $idGeneral
            ? Usuario::query()->whereKey($idGeneral)->value('Nombre')
            : null;
    }
}
