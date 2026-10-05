<?php

namespace App\Models\ADM;

use App\Models\Model;

class SistemaPerfil extends Model
{
    protected $connection = 'sqlsrv_1';
    protected $table = 'ADM_SISTEMA_PERFILES';
    protected $primaryKey = 'IdSistemaPerfil';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'IdSistema',
        'Descripcion',
        'Activo',
    ];

    protected $casts = [
        'Activo' => 'boolean',
    ];

    public function usuariosAreaSistema()
    {
        return $this->hasMany(AreaSistemaUsuario::class, 'IdSistemaPerfil', 'IdSistemaPerfil');
    }

    public function sistema()
    {
        return $this->belongsTo(Sistema::class, 'IdSistema', 'IdSistema');
    }

    public function permisos()
    {
        return $this->hasMany(SistemaPerfilPermiso::class, 'IdSistemaPerfil', 'IdSistemaPerfil');
    }

    public function permisosPantallas()
    {
        return $this->permisos()->pantallas();
    }

    public function permisosSecciones()
    {
        return $this->permisos()->secciones();
    }
}
