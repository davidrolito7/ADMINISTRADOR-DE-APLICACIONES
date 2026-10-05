<?php

namespace App\Models\ADM;

use App\Models\Model;

class Sistema extends Model
{
    protected $connection = 'sqlsrv_1';
    protected $table = 'ADM_SISTEMA';
    protected $primaryKey = 'IdSistema';
    public $timestamps = false;

    protected $fillable = [
        'NombreSis', //no nullable
        'Siglas', 
        'DescripcionSis',
        'DirectorioEnServidor',
        'Activo', //no nullable, default true
    ];

    protected $casts = [
        'Activo' => 'boolean',
    ];

    // Un sistema tiene muchas relaciones área-sistema
    public function areaSistemas()
    {
        return $this->hasMany(AreaSistema::class, 'idSistema', 'IdSistema');
    }

    // Acceso directo a las áreas a través de la tabla pivote
    public function areas()
    {
        return $this->belongsToMany(
            Area::class,
            'ADM_AREASISTEMA',
            'idSistema',
            'idArea'
        )->withPivot([
            'idAreaSistema',
            'fechaImplementacion',
            'fechaBaja',
            'direccionFTP',
            'usrFTP',
            'pwdFTP',
            'puertoFTP',
            'rutaDeInstalacionSistema',
            'Activo'
        ]);
    }
}
