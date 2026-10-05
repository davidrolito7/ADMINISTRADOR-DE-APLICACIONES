<?php

namespace App\Models\ADM;

use App\Models\Model;

class SistemaModulo extends Model
{
    protected $connection = 'sqlsrv_1';
    protected $table = 'ADM_SISTEMAMODULO';
    protected $primaryKey = 'IdSistemaModulo';
    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'Descripcion',
        'IdSistema',
        'FechaImplementacion',
        'FechaBaja',
        'OrdenGrupoMenu',
        'VisibleMenu',
        'Activo',
    ];

    protected $casts = [
        'FechaImplementacion' => 'date',
        'FechaBaja' => 'date',
        'VisibleMenu' => 'boolean',
        'Activo' => 'boolean',
    ];

    public function sistema()
    {
        return $this->belongsTo(Sistema::class, 'IdSistema', 'IdSistema');
    }

    public function pantallas()
    {
        return $this->hasMany(Pantalla::class, 'IdSistemaModulo', 'IdSistemaModulo');
    }

    public function permisos()
    {
        return $this->hasMany(SistemaPerfilPermiso::class, 'IdSistemaModulo', 'IdSistemaModulo')
            ->whereNotNull('IdPantalla')
            ->whereNull('IdSeccion');
    }
}
