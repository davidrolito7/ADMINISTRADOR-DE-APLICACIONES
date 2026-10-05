<?php

namespace App\Models\ADM;

use App\Models\Model;

class Seccion extends Model
{
    protected $connection = 'sqlsrv_1';
    protected $table = 'ADM_SECCION';
    protected $primaryKey = 'IdSeccion';
    public $timestamps = false;

    protected $fillable = [
        'IdPantalla',
        'Nombre',
        'Descripcion',
        'FechaAlta',
        'FechaBaja',
        'Activo',
    ];

    protected $casts = [
        'FechaAlta' => 'datetime',
        'FechaBaja' => 'datetime',
        'Activo' => 'boolean',
    ];

    public function pantalla()
    {
        return $this->belongsTo(Pantalla::class, 'IdPantalla', 'IdPantalla');
    }

    public function permisos()
    {
        return $this->hasMany(SistemaPerfilPermiso::class, 'IdSeccion', 'IdSeccion');
    }
}
