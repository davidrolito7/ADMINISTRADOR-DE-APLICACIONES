<?php

namespace App\Models\ADM;

use App\Models\Model;

class SistemaPerfilUsuario extends Model
{
    protected $connection = 'sqlsrv_1';
    protected $table = 'ADM_SISTEMA_PERFILES_USUARIOS';
    protected $primaryKey = 'IdUsuarioPerfil';
    public $timestamps = false;

    protected $fillable = [
        'IdSistemaPerfil',
        'IdAreaSistemaUsuario',
        'FechaAlta',
        'FechaBaja',
        'Activo',
        'IdCatMotivoJuezDespacho',
        'Observaciones',
        'IdCatMotivoJuezDespachoCancelacion',
        'ObservacionesCancelacion',
    ];

    protected $casts = [
        'FechaAlta' => 'datetime',
        'FechaBaja' => 'datetime',
        'Activo' => 'boolean',
    ];

    public function areaSistemaUsuario()
    {
        return $this->belongsTo(AreaSistemaUsuario::class, 'IdAreaSistemaUsuario', 'IdAreaSistemaUsuario');
    }

    public function sistemaPerfil()
    {
        return $this->belongsTo(SistemaPerfil::class, 'IdSistemaPerfil', 'IdSistemaPerfil');
    }
}
