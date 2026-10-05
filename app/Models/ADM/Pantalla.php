<?php

namespace App\Models\ADM;

use App\Models\Model;

class Pantalla extends Model
{
    protected $connection = 'sqlsrv_1';
    protected $table = 'ADM_PANTALLAS';
    protected $primaryKey = 'IdPantalla';
    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'Descripcion',
        'IdSistemaModulo',
        'FechaProduccion',
        'Ejecutable',
        'Parametros',
        'Valores',
        'Imagen',
        'VisibleMenu',
        'Acceso',
        'Orden',
        'Activo',
    ];

    protected $casts = [
        'FechaProduccion' => 'date',
        'VisibleMenu' => 'boolean',
        'Activo' => 'boolean',
    ];

    public function sistemaModulo()
    {
        return $this->belongsTo(SistemaModulo::class, 'IdSistemaModulo', 'IdSistemaModulo');
    }

    public function permisos()
    {
        return $this->hasMany(SistemaPerfilPermiso::class, 'IdPantalla', 'IdPantalla')
            ->whereNull('IdSeccion');
    }

    public function secciones()
    {
        return $this->hasMany(Seccion::class, 'IdPantalla', 'IdPantalla');
    }

    public function permisosSecciones()
    {
        return $this->hasMany(SistemaPerfilPermiso::class, 'IdPantalla', 'IdPantalla')
            ->whereNotNull('IdSeccion');
    }
}
