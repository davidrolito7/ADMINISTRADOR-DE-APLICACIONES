<?php

namespace App\Models\ADM;

use App\Models\Model;

class SistemaPerfilPermiso extends Model
{
    protected $connection = 'sqlsrv_1';
    protected $table = 'ADM_SISTEMA_PERFILES_PERMISOS';
    protected $primaryKey = 'IdObjetoPermiso';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'IdSistemaPerfil',
        'IdSistemaModulo',
        'IdPantalla',
        'IdSeccion',
        'FechaAlta',
        'FechaBaja',
        'Activo',
    ];

    protected $casts = [
        'FechaAlta' => 'datetime',
        'FechaBaja' => 'datetime',
        'Activo' => 'boolean',
    ];

    public function sistemaPerfil()
    {
        return $this->belongsTo(SistemaPerfil::class, 'IdSistemaPerfil', 'IdSistemaPerfil');
    }

    public function modulo()
    {
        return $this->belongsTo(SistemaModulo::class, 'IdSistemaModulo', 'IdSistemaModulo');
    }

    public function pantalla()
    {
        return $this->belongsTo(Pantalla::class, 'IdPantalla', 'IdPantalla');
    }

    public function seccion()
    {
        return $this->belongsTo(Seccion::class, 'IdSeccion', 'IdSeccion');
    }

    public function scopePantallas($query)
    {
        return $query
            ->whereNotNull('IdPantalla')
            ->whereNull('IdSeccion');
    }

    public function scopeSecciones($query)
    {
        return $query->whereNotNull('IdSeccion');
    }
}
