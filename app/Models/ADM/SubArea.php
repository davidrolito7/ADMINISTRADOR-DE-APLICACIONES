<?php

namespace App\Models\ADM;

use App\Models\Model;

class SubArea extends Model
{
    protected $connection = 'sqlsrv_1';
    protected $table = 'ADM_SUBAREA';
    protected $primaryKey = 'IdSubArea';
    public $timestamps = false;

    protected $fillable = [
        'Descripcion',
        'Activo',
    ];

    protected $casts = [
        'Activo' => 'boolean',
    ];

    // Una subárea puede tener muchos usuarios asignados
    public function usuariosAreaSistema()
    {
        return $this->hasMany(AreaSistemaUsuario::class, 'IdSubArea', 'IdSubArea');
    }
}
