<?php

namespace App\Models\ADM;

use App\Models\Model;

class CatCargo extends Model
{
    protected $connection = 'sqlsrv_1';

    protected $table = 'ADM_CatCargos';
    protected $primaryKey = 'IdCargo';
    public $timestamps = false;

    protected $fillable = [
        'Descripcion',
        'Activo',
    ];

    protected $casts = [
        'Activo' => 'boolean',
    ];

    // Un cargo puede tener muchos usuarios asignados
    public function usuariosAreaSistema()
    {
        return $this->hasMany(AreaSistemaUsuario::class, 'IdCargo', 'IdCargo');
    }
}
