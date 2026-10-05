<?php

namespace App\Models\ADM;

use App\Models\Catalogos\CatAreaJuzgado;
use App\Models\Model;


class Area extends Model
{
   protected $connection = 'sqlsrv_1';
    protected $table = 'ADM_AREA';
    protected $primaryKey = 'IdArea';
    public $timestamps = false;

    protected $fillable = [
        'idMunicipio',
        'IdRegion',
        'IdDistrito',
        'IdInstancia',
        'IdAnterior',
        'Nombre', //no nullable
        'Descripcion',
        'Dirección',
        'NoEmpleadoResponsableArea',
        'TelefonoContacto',
        'ExtensionContacto',
        'CorreoElectronicoContacto',
        'Georeferencia',
        'Teléfono',
        'Correo',
        'Oficialia',
        'Civil',
        'Familiar',
        'Mercantil',
        'Laboral',
        'Penal',
        'PenalOral',
        'Adolescentes',
        'Ejecución',
        'Administrativa',
        'Jurisdiccional',
        'Constitucional',
        'Indigena',
        'Observaciones',
        'Activo', //no nullable, default true
    ];

    protected $casts = [
        'Activo' => 'boolean',
    ];

    // Un área tiene muchas relaciones área-sistema
    public function areaSistemas()
    {
        return $this->hasMany(AreaSistema::class, 'idArea', 'IdArea');
    }

    public function areaJuzgado()
    {
        return $this->hasOne(CatAreaJuzgado::class, 'idArea', 'IdArea');
    }
}
