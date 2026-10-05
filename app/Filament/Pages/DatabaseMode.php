<?php

namespace App\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\DB;

class DatabaseMode extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;

    protected static ?string $navigationLabel = 'Entorno de desarrollo';

    protected static string|\UnitEnum|null $navigationGroup = 'Configuración';

    protected static ?string $title = 'Entorno de desarrollo';

    protected string $view = 'filament.pages.database-mode';

    public string $mode = 'production';

    public function mount(): void
    {
        $this->mode = session('adm_database_mode', 'production');
    }

    public function selectMode(string $mode): void
    {
        if (! in_array($mode, ['production', 'testing'], true)) {
            return;
        }

        session(['adm_database_mode' => $mode]);
        $this->mode = $mode;

        config([
            'database.connections.sqlsrv_1' => array_merge(
                config('database.connections.sqlsrv_1'),
                config("database.adm_connection_modes.{$mode}"),
            ),
        ]);
        DB::purge('sqlsrv_1');

        Notification::make()
            ->success()
            ->title($mode === 'production' ? 'Modo producción activado' : 'Modo pruebas activado')
            ->body('La conexión se aplicará en las siguientes consultas de esta sesión.')
            ->send();
    }
}
