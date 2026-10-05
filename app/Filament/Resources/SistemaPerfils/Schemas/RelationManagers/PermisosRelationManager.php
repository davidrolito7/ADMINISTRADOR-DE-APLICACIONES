<?php

namespace App\Filament\Resources\SistemaPerfils\Schemas\RelationManagers;

use App\Models\ADM\Pantalla;
use App\Models\ADM\Seccion;
use App\Models\ADM\SistemaModulo;
use App\Models\ADM\SistemaPerfilPermiso;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class PermisosRelationManager extends RelationManager
{
    protected static string $relationship = 'permisos';
    protected static ?string $title = 'Permisos del perfil';
    protected static ?string $modelLabel = 'Modulo del perfil';
    protected static ?string $pluralModelLabel = 'Modulos del perfil';

    public function form(Schema $schema): Schema
    {
        return $schema->schema($this->getCreateSchema());
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                $sub = DB::connection($query->getModel()->getConnectionName())
                    ->table('ADM_SISTEMA_PERFILES_PERMISOS')
                    ->join('ADM_SISTEMAMODULO', 'ADM_SISTEMAMODULO.IdSistemaModulo', '=', 'ADM_SISTEMA_PERFILES_PERMISOS.IdSistemaModulo')
                    ->whereNotNull('ADM_SISTEMA_PERFILES_PERMISOS.IdPantalla')
                    ->where('ADM_SISTEMA_PERFILES_PERMISOS.Activo', true)
                    ->groupBy('ADM_SISTEMAMODULO.IdSistemaModulo', 'ADM_SISTEMAMODULO.Nombre', 'ADM_SISTEMA_PERFILES_PERMISOS.IdSistemaPerfil')
                    ->selectRaw('MIN(ADM_SISTEMA_PERFILES_PERMISOS.IdObjetoPermiso) as IdObjetoPermiso')
                    ->selectRaw('ADM_SISTEMA_PERFILES_PERMISOS.IdSistemaPerfil')
                    ->selectRaw('MIN(ADM_SISTEMA_PERFILES_PERMISOS.IdSistemaModulo) as IdSistemaModulo')
                    ->selectRaw('MIN(ADM_SISTEMA_PERFILES_PERMISOS.IdPantalla) as IdPantalla')
                    ->selectRaw('NULL as IdSeccion')
                    ->selectRaw('MAX(ADM_SISTEMA_PERFILES_PERMISOS.FechaAlta) as FechaAlta')
                    ->selectRaw('MAX(ADM_SISTEMA_PERFILES_PERMISOS.FechaBaja) as FechaBaja')
                    ->selectRaw('MAX(CASE WHEN ADM_SISTEMA_PERFILES_PERMISOS.Activo = 1 THEN 1 ELSE 0 END) as Activo')
                    ->selectRaw('MIN(ADM_SISTEMAMODULO.IdSistemaModulo) as modulo_id')
                    ->selectRaw('ADM_SISTEMAMODULO.Nombre as modulo_nombre');

                $inner = $query->getQuery();
                $inner->joins = null;
                $inner->groups = null;
                $inner->columns = null;

                $query->fromSub($sub, 'ADM_SISTEMA_PERFILES_PERMISOS');
                $query->select('ADM_SISTEMA_PERFILES_PERMISOS.*');
                $query->whereNotNull('ADM_SISTEMA_PERFILES_PERMISOS.IdPantalla');
                $query->where('ADM_SISTEMA_PERFILES_PERMISOS.Activo', true);
            })
            ->defaultSort('modulo_nombre')
            ->recordTitleAttribute('modulo_nombre')
            ->recordAction('editarModulo')
            ->columns([
                TextColumn::make('modulo_nombre')
                    ->label('Modulo')
                    ->searchable(),

                TextColumn::make('pantallas_count')
                    ->label('Pantallas')
                    ->state(fn(SistemaPerfilPermiso $record): string => (string) count($this->getPantallasSeleccionadasModulo((int) $record->modulo_id))),

                TextColumn::make('secciones_count')
                    ->label('Secciones')
                    ->state(fn(SistemaPerfilPermiso $record): string => (string) count($this->getSeccionesSeleccionadasModulo((int) $record->modulo_id))),

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
                        true: fn($query) => $query->where('ADM_SISTEMA_PERFILES_PERMISOS.Activo', true),
                        false: fn($query) => $query->whereRaw('1 = 0'),
                    ),
            ])
            ->headerActions([
                Action::make('asignarPermisos')
                    ->label('Asignar modulo')
                    ->modalHeading('Asignar modulo, pantallas y secciones al perfil')
                    ->modalSubmitActionLabel('Guardar')
                    ->schema($this->getCreateSchema())
                    ->action(function (array $data): void {
                        if (! $this->hasPantallaHabilitada($data['pantallas_config'] ?? [])) {
                            Notification::make()
                                ->title('Debes seleccionar al menos una pantalla.')
                                ->danger()
                                ->send();

                            return;
                        }

                        $asignados = $this->storePermisos($data);

                        Notification::make()
                            ->title($asignados > 0
                                ? "Se asignaron {$asignados} permisos al modulo"
                                : 'No se seleccionó ninguna pantalla o sección')
                            ->success()
                            ->send();
                    }),
            ])
            ->recordActions([
                Action::make('editarModulo')
                    ->label('Editar')
                    ->modalHeading(fn(SistemaPerfilPermiso $record): string => 'Pantallas del modulo: ' . ($record->modulo_nombre ?? ''))
                    ->modalSubmitActionLabel('Guardar')
                    ->fillForm(fn(SistemaPerfilPermiso $record): array => [
                        'IdSistemaModulo' => (int) $record->modulo_id,
                        'pantallas_config' => $this->getPantallasConfigModulo((int) $record->modulo_id),
                        'secciones_por_pantalla' => $this->getSeccionesSeleccionadasPorPantalla((int) $record->modulo_id),
                    ])
                    ->schema($this->getEditSchema())
                    ->action(function (array $data, SistemaPerfilPermiso $record): void {
                        if (! $this->hasPantallaHabilitada($data['pantallas_config'] ?? [])) {
                            Notification::make()
                                ->title('Debes seleccionar al menos una pantalla.')
                                ->danger()
                                ->send();

                            return;
                        }

                        $this->syncModuloPermisos((int) $record->modulo_id, $data);

                        Notification::make()
                            ->title('Permisos del modulo actualizados')
                            ->success()
                            ->send();
                    }),

                DeleteAction::make()
                    ->before(function (SistemaPerfilPermiso $record, DeleteAction $action) {
                        $this->deactivateModuloPermisos((int) $record->modulo_id);
                        $action->success();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function ($records, DeleteBulkAction $action) {
                            $records->each(fn(SistemaPerfilPermiso $record) => $this->deactivateModuloPermisos((int) $record->modulo_id));
                            $action->success();
                        }),
                ]),
            ]);
    }

    protected function getCreateSchema(): array
    {
        return [
            Select::make('IdSistemaModulo')
                ->label('Modulo')
                ->options(fn(): array => $this->getModuloOptions())
                ->searchable()
                ->required()
                ->afterStateUpdated(function (Select $component, $state, callable $set): void {
                    $set('pantallas_config', []);
                    $set('secciones_por_pantalla', []);

                    $component
                        ->getContainer()
                        ->getComponent('pantallasYSecciones')
                        ?->getChildSchema()
                        ?->fill([
                            'IdSistemaModulo' => $state,
                            'pantallas_config' => [],
                            'secciones_por_pantalla' => [],
                        ]);
                })
                ->live(),

            ...$this->getPantallasYSeccionesSchema(),
        ];
    }

    protected function getEditSchema(): array
    {
        return [
            Select::make('IdSistemaModulo')
                ->label('Modulo')
                ->options(fn(): array => $this->getModuloOptions())
                ->disabled()
                ->dehydrated(false),

            ...$this->getPantallasYSeccionesSchema(),
        ];
    }

    protected function getPantallasYSeccionesSchema(): array
    {
        return [

            Grid::make(1)
                ->key('pantallasYSecciones')
                ->schema(fn(callable $get): array => $this->buildPantallasSections($get('IdSistemaModulo')))
                ->columnSpanFull(),
        ];
    }

    protected function buildPantallasSections(null|int|string $idSistemaModulo): array
    {
        if (blank($idSistemaModulo)) {
            return [];
        }

        return Pantalla::query()
            ->where('Activo', true)
            ->where('IdSistemaModulo', $idSistemaModulo)
            ->orderBy('Orden')
            ->get(['IdPantalla', 'Nombre'])
            ->map(function (Pantalla $pantalla): Section {
                $secciones = $this->getSeccionesOptionsPantalla((int) $pantalla->IdPantalla);

                return Section::make($pantalla->Nombre)
                    ->schema([
                        Toggle::make("pantallas_config.{$pantalla->IdPantalla}.habilitada")
                            ->label('Permitir pantalla')
                            ->default(false)
                            ->live()
                            ->afterStateUpdated(function (bool $state, callable $set) use ($pantalla): void {
                                if (! $state) {
                                    $set("secciones_por_pantalla.{$pantalla->IdPantalla}", []);
                                }
                            }),

                        ...(
                            filled($secciones)
                                ? [
                                    CheckboxList::make("secciones_por_pantalla.{$pantalla->IdPantalla}")
                                        ->label('Secciones')
                                        ->options($secciones)
                                        ->hidden(fn(callable $get): bool => ! (bool) $get("pantallas_config.{$pantalla->IdPantalla}.habilitada"))
                                        ->helperText('Si no seleccionas secciones, el permiso aplica a toda la pantalla.')
                                        ->searchable()
                                        ->columns(2)
                                        ->columnSpanFull(),
                                ]
                                : []
                        ),
                    ])
                    ->compact();
            })
            ->all();
    }

    protected function getModuloOptions(): array
    {
        return SistemaModulo::query()
            ->where('Activo', true)
            ->where('IdSistema', $this->getOwnerRecord()->IdSistema)
            ->orderBy('OrdenGrupoMenu')
            ->pluck('Nombre', 'IdSistemaModulo')
            ->all();
    }

    protected function getSeccionesOptionsPantalla(int $idPantalla): array
    {
        return Seccion::query()
            ->where('Activo', true)
            ->where('IdPantalla', $idPantalla)
            ->orderBy('Nombre')
            ->pluck('Nombre', 'IdSeccion')
            ->all();
    }

    protected function getPantallasSeleccionadasModulo(int $idSistemaModulo): array
    {
        return SistemaPerfilPermiso::query()
            ->where('IdSistemaPerfil', $this->getOwnerRecord()->IdSistemaPerfil)
            ->where('IdSistemaModulo', $idSistemaModulo)
            ->whereNotNull('IdPantalla')
            ->where('Activo', true)
            ->pluck('IdPantalla')
            ->unique()
            ->map(fn($id): int => (int) $id)
            ->all();
    }

    protected function getSeccionesSeleccionadasModulo(int $idSistemaModulo): array
    {
        return SistemaPerfilPermiso::query()
            ->where('IdSistemaPerfil', $this->getOwnerRecord()->IdSistemaPerfil)
            ->where('IdSistemaModulo', $idSistemaModulo)
            ->whereNotNull('IdSeccion')
            ->where('Activo', true)
            ->pluck('IdSeccion')
            ->map(fn($id): int => (int) $id)
            ->all();
    }

    protected function getPantallasConfigModulo(int $idSistemaModulo): array
    {
        return collect($this->getPantallasSeleccionadasModulo($idSistemaModulo))
            ->mapWithKeys(fn(int $idPantalla): array => [
                $idPantalla => ['habilitada' => true],
            ])
            ->all();
    }

    protected function getSeccionesSeleccionadasPorPantalla(int $idSistemaModulo): array
    {
        return SistemaPerfilPermiso::query()
            ->where('IdSistemaPerfil', $this->getOwnerRecord()->IdSistemaPerfil)
            ->where('IdSistemaModulo', $idSistemaModulo)
            ->whereNotNull('IdSeccion')
            ->where('Activo', true)
            ->get(['IdPantalla', 'IdSeccion'])
            ->groupBy('IdPantalla')
            ->map(fn($items): array => $items
                ->pluck('IdSeccion')
                ->map(fn($id): int => (int) $id)
                ->values()
                ->all())
            ->all();
    }

    protected function storePermisos(array $data): int
    {
        return $this->syncModuloPermisos((int) $data['IdSistemaModulo'], $data);
    }

    protected function syncModuloPermisos(int $idSistemaModulo, array $data): int
    {
        $pantallasModulo = Pantalla::query()
            ->where('Activo', true)
            ->where('IdSistemaModulo', $idSistemaModulo)
            ->pluck('IdPantalla')
            ->map(fn($id): int => (int) $id)
            ->all();

        $pantallasConfig = $this->normalizePantallasConfig(
            $pantallasModulo,
            $data['pantallas_config'] ?? []
        );
        $seccionesPorPantalla = $this->normalizeSeccionesPorPantalla($pantallasModulo, $data['secciones_por_pantalla'] ?? []);

        $pantallasValidas = collect($pantallasModulo)
            ->filter(fn(int $idPantalla): bool => (bool) data_get($pantallasConfig, "{$idPantalla}.habilitada", false))
            ->values()
            ->all();

        $seccionesSeleccionadas = collect($pantallasValidas)
            ->flatMap(fn(int $idPantalla): array => array_map(
                'intval',
                data_get($seccionesPorPantalla, (string) $idPantalla, []) ?? []
            ))
            ->unique()
            ->values()
            ->all();

        $seccionesValidas = Seccion::query()
            ->whereIn('IdPantalla', $pantallasValidas)
            ->whereIn('IdSeccion', $seccionesSeleccionadas)
            ->get(['IdSeccion', 'IdPantalla']);

        $pantallasConSecciones = $seccionesValidas
            ->pluck('IdPantalla')
            ->map(fn($id): int => (int) $id)
            ->unique()
            ->all();

        $pantallasSolo = array_values(array_diff($pantallasValidas, $pantallasConSecciones));

        SistemaPerfilPermiso::query()
            ->where('IdSistemaPerfil', $this->getOwnerRecord()->IdSistemaPerfil)
            ->where('IdSistemaModulo', $idSistemaModulo)
            ->update([
                'Activo' => false,
                'FechaBaja' => now(),
            ]);

        foreach ($pantallasSolo as $idPantalla) {
            if ($this->upsertPermiso([
                'IdSistemaPerfil' => $this->getOwnerRecord()->IdSistemaPerfil,
                'IdSistemaModulo' => $idSistemaModulo,
                'IdPantalla' => $idPantalla,
                'IdSeccion' => null,
            ], true)) {
            }
        }

        foreach ($seccionesValidas as $seccion) {
            if ($this->upsertPermiso([
                'IdSistemaPerfil' => $this->getOwnerRecord()->IdSistemaPerfil,
                'IdSistemaModulo' => $idSistemaModulo,
                'IdPantalla' => (int) $seccion->IdPantalla,
                'IdSeccion' => (int) $seccion->IdSeccion,
            ], true)) {
            }
        }

        return SistemaPerfilPermiso::query()
            ->where('IdSistemaPerfil', $this->getOwnerRecord()->IdSistemaPerfil)
            ->where('IdSistemaModulo', $idSistemaModulo)
            ->whereNotNull('IdPantalla')
            ->where('Activo', true)
            ->count();
    }

    protected function normalizePantallasConfig(array $pantallasModulo, array $pantallasConfig): array
    {
        return collect($pantallasModulo)
            ->values()
            ->mapWithKeys(function (int $idPantalla, int $index) use ($pantallasConfig): array {
                $config = $pantallasConfig[$idPantalla]
                    ?? $pantallasConfig[(string) $idPantalla]
                    ?? $pantallasConfig[$index]
                    ?? [];

                return [
                    $idPantalla => [
                        'habilitada' => (bool) data_get($config, 'habilitada', false),
                    ],
                ];
            })
            ->all();
    }

    protected function hasPantallaHabilitada(array $pantallasConfig): bool
    {
        return collect($pantallasConfig)
            ->contains(fn ($config): bool => (bool) data_get($config, 'habilitada', false));
    }

    protected function normalizeSeccionesPorPantalla(array $pantallasModulo, array $seccionesPorPantalla): array
    {
        return collect($pantallasModulo)
            ->values()
            ->mapWithKeys(function (int $idPantalla, int $index) use ($seccionesPorPantalla): array {
                $seleccionadas = $seccionesPorPantalla[$idPantalla]
                    ?? $seccionesPorPantalla[(string) $idPantalla]
                    ?? $seccionesPorPantalla[$index]
                    ?? [];

                return [
                    $idPantalla => collect($seleccionadas)
                        ->map(fn($idSeccion): int => (int) $idSeccion)
                        ->values()
                        ->all(),
                ];
            })
            ->all();
    }

    protected function deactivateModuloPermisos(int $idSistemaModulo): void
    {
        SistemaPerfilPermiso::query()
            ->where('IdSistemaPerfil', $this->getOwnerRecord()->IdSistemaPerfil)
            ->where('IdSistemaModulo', $idSistemaModulo)
            ->update([
                'Activo' => false,
                'FechaBaja' => now(),
            ]);
    }

    protected function upsertPermiso(array $attributes, bool $activo): bool
    {
        $connection = DB::connection((new SistemaPerfilPermiso())->getConnectionName());
        $table = (new SistemaPerfilPermiso())->getTable();

        $existing = $connection
            ->table($table)
            ->where('IdSistemaPerfil', $attributes['IdSistemaPerfil'])
            ->where('IdSistemaModulo', $attributes['IdSistemaModulo'])
            ->where('IdPantalla', $attributes['IdPantalla'])
            ->when(
                array_key_exists('IdSeccion', $attributes) && $attributes['IdSeccion'] !== null,
                fn($query) => $query->where('IdSeccion', $attributes['IdSeccion']),
                fn($query) => $query->whereNull('IdSeccion')
            )
            ->first(['IdObjetoPermiso', 'FechaAlta']);

        $payload = [
            ...$attributes,
            'FechaBaja' => null,
            'Activo' => $activo,
        ];

        if ($existing) {
            return $connection
                ->table($table)
                ->where('IdObjetoPermiso', $existing->IdObjetoPermiso)
                ->update([
                    ...$payload,
                    'FechaAlta' => $existing->FechaAlta ?? now(),
                ]) >= 0;
        }

        return $connection
            ->table($table)
            ->insert([
                ...$payload,
                'FechaAlta' => now(),
            ]);
    }
}
