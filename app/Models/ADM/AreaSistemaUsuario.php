<?php

namespace App\Models\ADM;

use App\Models\Model;
use App\Models\Usuario;

class AreaSistemaUsuario extends Model
{
    protected $connection = 'sqlsrv_1';
    protected $table = 'ADM_AREASSISTEMASUSUARIOS';
    protected $primaryKey = 'IdAreaSistemaUsuario';
    public $timestamps = false;

    protected $fillable = [
        'IdAreaSistema', // fk a AreaSistema
        'IdGeneral', //fk a Usuario
        'IdCargo', //fk a CatCargo
        'IdSubArea', //fk a SubArea
        'FechaAlta', //no nullable, default current timestamp
        'FechaBaja',
        'Observaciones',
        'Activo', //no nullable, default true
    ];

    protected $casts = [
        'FechaAlta' => 'datetime',
        'FechaBaja' => 'datetime',
        'Activo'    => 'boolean',
    ];

    // FK -> ADM_AREASISTEMA
    public function areaSistema()
    {
        return $this->belongsTo(AreaSistema::class, 'IdAreaSistema', 'idAreaSistema');
    }

    // FK -> ADM_CatCargos
    public function cargo()
    {
        return $this->belongsTo(CatCargo::class, 'IdCargo', 'IdCargo');
    }

    // FK -> ADM_SUBAREA
    public function subArea()
    {
        return $this->belongsTo(SubArea::class, 'IdSubArea', 'IdSubArea');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'IdGeneral', 'IdGeneral');
    }

    // En AreaSistemaUsuario.php
    public function perfiles()
    {
        return $this->hasMany(SistemaPerfilUsuario::class, 'IdAreaSistemaUsuario', 'IdAreaSistemaUsuario');
    }

    public function perfilActivo()
    {
        return $this->hasOne(SistemaPerfilUsuario::class, 'IdAreaSistemaUsuario', 'IdAreaSistemaUsuario')
            ->where('Activo', true)
            ->latestOfMany('IdUsuarioPerfil');
    }

    
}
