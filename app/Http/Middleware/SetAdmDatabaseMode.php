<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SetAdmDatabaseMode
{
    public function handle(Request $request, Closure $next): Response
    {
        $mode = $request->session()->get('adm_database_mode', 'production');
        $modeConfiguration = config("database.adm_connection_modes.{$mode}");

        if (! is_array($modeConfiguration)) {
            $mode = 'production';
            $modeConfiguration = config('database.adm_connection_modes.production');
            $request->session()->put('adm_database_mode', $mode);
        }

        config([
            'database.connections.sqlsrv_1' => array_merge(
                config('database.connections.sqlsrv_1'),
                $modeConfiguration,
            ),
        ]);

        DB::purge('sqlsrv_1');

        return $next($request);
    }
}
