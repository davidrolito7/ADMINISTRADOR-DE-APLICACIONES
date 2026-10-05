<?php

namespace App\Models\ADM;

use App\Models\Model;

class AreaSistema extends Model
{
    protected $connection = 'sqlsrv_1';
    protected $table = 'ADM_AREASISTEMA';
    protected $primaryKey = 'idAreaSistema';
    public $timestamps = false;

    protected $fillable = [
        'idArea', //fk a Area
        'idSistema',  //fk a Sistema
        'fechaImplementacion', //no nullable
        'fechaBaja',
        'Activo', //no nullable, default true
    ];

    protected $casts = [
        'fechaImplementacion' => 'date',
        'fechaBaja'           => 'date',
        'Activo'              => 'boolean',
        'Identificador'       => 'string',
    ];

    // FK -> ADM_AREA
    public function area()
    {
        return $this->belongsTo(Area::class, 'idArea', 'IdArea');
    }

    // FK -> ADM_SISTEMA
    public function sistema()
    {
        return $this->belongsTo(Sistema::class, 'idSistema', 'IdSistema');
    }

    // Un área-sistema tiene muchos usuarios
    public function usuarios()
    {
        return $this->hasMany(AreaSistemaUsuario::class, 'IdAreaSistema', 'idAreaSistema');
    }

    public function usuarioPerfiles()
{
    return $this->hasManyThrough(
        SistemaPerfilUsuario::class,
        AreaSistemaUsuario::class,
        'IdAreaSistema',      // FK en AreaSistemaUsuario
        'IdAreaSistemaUsuario', // FK en SistemaPerfilUsuario
        'idAreaSistema',      // PK local
        'IdAreaSistemaUsuario' // PK en AreaSistemaUsuario
    );
}
}
