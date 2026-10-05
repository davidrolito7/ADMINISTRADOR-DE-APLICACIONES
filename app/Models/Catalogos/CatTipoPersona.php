<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;
use App\Models\Abogado;

class CatTipoPersona extends Model
{
    protected $connection = 'sqlsrv_2';
    protected $table = 'PD_CatTipoPersona';
    protected $primaryKey = 'idCatTipoPersona';
    public $timestamps = false;

    protected $fillable = [
        'Descripcion',
        'Activo',
    ];

    protected $casts = [
        'Activo' => 'boolean',
    ];

    public function abogados()
    {
        return $this->hasMany(Abogado::class, 'idTipoPersona', 'idCatTipoPersona');
    }
}
