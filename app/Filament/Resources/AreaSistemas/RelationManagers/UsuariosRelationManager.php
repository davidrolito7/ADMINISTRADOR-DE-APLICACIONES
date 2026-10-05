<?php

namespace App\Filament\Resources\AreaSistemas\RelationManagers;

use App\Models\ADM\AreaSistemaUsuario;
use App\Models\ADM\CatCargo;
use App\Models\ADM\SistemaPerfil;
use App\Models\ADM\SistemaPerfilUsuario;
use App\Models\ADM\SubArea;
use App\Models\Usuario;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class UsuariosRelationManager extends RelationManager
{
    protected static string $relationship = 'usuarios';
    protected static ?string $title = 'Usuarios asignados';
    protected static ?string $modelLabel = 'Usuario';           // ← agrega esto
    protected static ?string $pluralModelLabel = 'Usuarios';
    //protected static ?string $relatedResource = AreaSistemaResource::class;
    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        $count = $ownerRecord->usuarios()->count();
        return "Usuarios asignados ({$count})";
    }
    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('IdGeneral')
                ->label('Usuario')
                ->searchable()
                ->getSearchResultsUsing(function (string $search): array {
                    return Usuario::query()
                        ->where(function ($query) use ($search): void {
                            $query->where('Nombre', 'like', "%{$search}%");

                            if (is_numeric($search)) {
                                $query->orWhere('IdGeneral', (int) $search);
                            }
                        })
                        ->orderBy('Nombre')
                        ->limit(50)
                        ->get(['IdGeneral', 'Nombre'])
                        ->mapWithKeys(fn(Usuario $usuario): array => [
                            $usuario->IdGeneral => "{$usuario->IdGeneral} - {$usuario->Nombre}",
                        ])
                        ->all();
                })
                ->getOptionLabelUsing(function ($value): ?string {
                    $usuario = Usuario::query()
                        ->whereKey($value)
                        ->first(['IdGeneral', 'Nombre']);

                    return $usuario
                        ? "{$usuario->IdGeneral} - {$usuario->Nombre}"
                        : null;
                })
                ->required(),

            Select::make('IdCargo')
                ->label('Cargo')
                ->options(
                    CatCargo::where('Activo', true)
                        ->pluck('Descripcion', 'IdCargo')
                )
                ->searchable()
                ->required(),

            Select::make('IdSubArea')
                ->label('Subárea')
                ->options(
                    SubArea::where('Activo', true)
                        ->pluck('Descripcion', 'IdSubArea')
                )
                ->searchable()
                ->required(),

            DateTimePicker::make('FechaAlta')
                ->label('Fecha alta')
                ->default(now())
                ->native(false)
                ->displayFormat('d/m/Y H:i')
                ->required(),

            DateTimePicker::make('FechaBaja')
                ->label('Fecha baja')
                ->native(false)
                ->displayFormat('d/m/Y H:i'),

            Textarea::make('Observaciones')
                ->label('Observaciones')
                ->columnSpanFull(),

            Toggle::make('Activo')
                ->label('Activo')
                ->default(true),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('IdGeneral')
            ->columns([
                TextColumn::make('IdGeneral')
                    ->label('ID General')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('usuario.Nombre')
                    ->label('Nombre'),
                    //->searchable(),
                TextColumn::make('cargo.Descripcion')
                    ->label('Cargo')
                    ->sortable(),

                TextColumn::make('subArea.Descripcion')
                    ->label('Subárea')
                    ->sortable(),

                TextColumn::make('subArea.IdSubArea')
                    ->label('IdSubArea')
                    ->sortable(),

                TextColumn::make('FechaAlta')
                    ->label('Fecha alta')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('—'),

                TextColumn::make('FechaBaja')
                    ->label('Fecha baja')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('—'),

                TextColumn::make('Observaciones')
                    ->label('Observaciones')
                    ->limit(30)
                    ->placeholder('—'),

                IconColumn::make('Activo')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('Activo')
                    ->label('Activo'),


            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Asignar usuario')
                    ->successNotificationTitle('Usuario asignado. Usa Perfiles en su fila para asignarle accesos.'),
            ])
            ->recordActions([
                Action::make('perfiles')
                    ->label('Perfiles')
                    ->icon('heroicon-o-identification')
                    ->color('primary')
                    ->modalHeading(fn(AreaSistemaUsuario $record): string => "Perfiles de {$record->usuario?->Nombre}")
                    ->modalDescription('Selecciona los perfiles que tendrá este usuario en el sistema actual.')
                    ->modalSubmitActionLabel('Guardar perfiles')
                    ->schema([
                        Hidden::make('perfilesIniciales'),

                        CheckboxList::make('perfiles')
                            ->label('Perfiles disponibles')
                            ->options(fn(): array => $this->getPerfilOptions())
                            ->columns(2)
                            ->bulkToggleable()
                            ->live(),

                        Textarea::make('observacionBaja')
                            ->label('Observación de bajas')
                            ->helperText('Es obligatoria solo si desmarcas uno o más perfiles.')
                            ->visible(fn(Get $get): bool => $this->hasPerfilesParaDarBaja(
                                $get('perfilesIniciales'),
                                $get('perfiles'),
                            ))
                            ->required(fn(Get $get): bool => $this->hasPerfilesParaDarBaja(
                                $get('perfilesIniciales'),
                                $get('perfiles'),
                            ))
                            ->rows(3)
                            ->maxLength(1000),
                    ])
                    ->fillForm(fn(AreaSistemaUsuario $record): array => [
                        'perfiles' => $perfiles = $record->perfiles()
                            ->where('Activo', true)
                            ->pluck('IdSistemaPerfil')
                            ->map(fn($id): int => (int) $id)
                            ->all(),
                        'perfilesIniciales' => $perfiles,
                    ])
                    ->action(function (array $data, AreaSistemaUsuario $record): void {
                        $perfilesSeleccionados = collect($data['perfiles'] ?? [])
                            ->map(fn($id): int => (int) $id)
                            ->unique()
                            ->values();

                        $hayBajas = $record->perfiles()
                            ->where('Activo', true)
                            ->whereNotIn('IdSistemaPerfil', $perfilesSeleccionados->all())
                            ->exists();

                        if ($hayBajas && blank($data['observacionBaja'] ?? null)) {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('Indica el motivo de la baja')
                                ->body('Escribe una observación antes de quitar perfiles al usuario.')
                                ->send();

                            return;
                        }

                        $changes = $this->syncPerfiles(
                            $record,
                            $perfilesSeleccionados->all(),
                            $data['observacionBaja'] ?? null,
                        );

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Perfiles actualizados')
                            ->body("{$changes['created']} asignado(s) y {$changes['deactivated']} dado(s) de baja.")
                            ->send();
                    }),

                EditAction::make(),
                DeleteAction::make()
                    ->label('Dar de baja')
                    ->modalHeading('Dar de baja al usuario')
                    ->modalDescription('El usuario dejará de estar activo en este sistema. Sus perfiles activos también se darán de baja.')
                    ->modalSubmitActionLabel('Confirmar baja')
                    ->successNotificationTitle('Usuario dado de baja')
                    ->schema([
                        Textarea::make('observacionBaja')
                            ->label('Observación')
                            ->required()
                            ->rows(3)
                            ->maxLength(1000),
                    ])
                    ->hidden(fn(AreaSistemaUsuario $record): bool => ! $record->Activo)
                    ->using(function (array $data, AreaSistemaUsuario $record): bool {
                        DB::connection('sqlsrv_1')->transaction(function () use ($data, $record): void {
                            $fechaBaja = now();
                            $observacion = $this->appendObservation($record->Observaciones, $data['observacionBaja']);

                            $record->update([
                                'Activo' => false,
                                'FechaBaja' => $fechaBaja,
                                'Observaciones' => $observacion,
                            ]);

                            $perfilesActivos = $record->perfiles()
                                ->where('Activo', true)
                                ->get();

                            $perfilesActivos->each(function (SistemaPerfilUsuario $perfil) use ($data, $fechaBaja): void {
                                $perfil->update([
                                    'Activo' => false,
                                    'FechaBaja' => $fechaBaja,
                                    'Observaciones' => $this->appendObservation($perfil->Observaciones, $data['observacionBaja']),
                                ]);
                            });
                        });

                        return true;
                    }),
            ]);
    }

    /**
     * @return array<int, string>
     */
    protected function getPerfilOptions(): array
    {
        return SistemaPerfil::query()
            ->where('Activo', true)
            ->where('IdSistema', $this->getOwnerRecord()->idSistema)
            ->orderBy('Descripcion')
            ->pluck('Descripcion', 'IdSistemaPerfil')
            ->all();
    }

    /**
     * Sincroniza los perfiles activos y conserva los registros inactivos como historial.
     *
     * @param array<int, int|string> $perfilIds
     * @return array{created: int, deactivated: int}
     */
    protected function syncPerfiles(AreaSistemaUsuario $usuario, array $perfilIds, ?string $observacionBaja): array
    {
        $perfilIds = collect($perfilIds)
            ->map(fn($id): int => (int) $id)
            ->unique()
            ->values();

        return DB::connection('sqlsrv_1')->transaction(function () use ($usuario, $perfilIds, $observacionBaja): array {
            $activos = $usuario->perfiles()
                ->where('Activo', true)
                ->pluck('IdSistemaPerfil')
                ->map(fn($id): int => (int) $id);

            $aDesactivar = $activos->diff($perfilIds);
            $aCrear = $perfilIds->diff($activos);

            if ($aDesactivar->isNotEmpty()) {
                $perfilesABaja = $usuario->perfiles()
                    ->where('Activo', true)
                    ->whereIn('IdSistemaPerfil', $aDesactivar->all())
                    ->get();

                $perfilesABaja->each(function (SistemaPerfilUsuario $perfil) use ($observacionBaja): void {
                    $perfil->update([
                        'Activo' => false,
                        'FechaBaja' => now(),
                        'Observaciones' => $this->appendObservation($perfil->Observaciones, $observacionBaja),
                    ]);
                });
            }

            foreach ($aCrear as $idSistemaPerfil) {
                SistemaPerfilUsuario::create([
                    'IdAreaSistemaUsuario' => $usuario->IdAreaSistemaUsuario,
                    'IdSistemaPerfil' => $idSistemaPerfil,
                    'FechaAlta' => now(),
                    'Activo' => true,
                ]);
            }

            return [
                'created' => $aCrear->count(),
                'deactivated' => $aDesactivar->count(),
            ];
        });
    }

    protected function hasPerfilesParaDarBaja(mixed $perfilesIniciales, mixed $perfilesSeleccionados): bool
    {
        $iniciales = collect($perfilesIniciales ?? [])
            ->map(fn($id): int => (int) $id);
        $seleccionados = collect($perfilesSeleccionados ?? [])
            ->map(fn($id): int => (int) $id);

        return $iniciales->diff($seleccionados)->isNotEmpty();
    }

    protected function appendObservation(?string $previous, ?string $observation): ?string
    {
        $observation = trim((string) $observation);

        if ($observation === '') {
            return $previous;
        }

        return filled($previous)
            ? trim($previous) . PHP_EOL . $observation
            : $observation;
    }
}
