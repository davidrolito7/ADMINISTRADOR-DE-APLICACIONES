<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Catalogos\CatTipoPersona;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Usuario extends Model
{
    protected $connection = 'sqlsrv_2';
    protected $table = 'PD_Abogados';
    protected $primaryKey = 'IdGeneral';
    public $timestamps = false;

    protected $fillable = [
        'Folio',
        'Nombre',
        'IdTitulo',
        'CURP',
        'Direccion',
        'DireccionPart',
        'Correo',
        'CorreoAlterno',
        'Celular',
        'Telefono',
        'IdBarra',
        'idEstatus',
        'Observaciones',
        'idTipoPersona',
        'FechaAlta',
        'NoEmpleado',
        'CorreoVerificado',
        'Activo',
    ];

    protected $casts = [
        'CorreoVerificado'  => 'boolean',
        'Activo'            => 'boolean',
    ];

    public function tipoPersona()
    {
        return $this->belongsTo(CatTipoPersona::class, 'idTipoPersona', 'idCatTipoPersona');
    }

    public function userProfile()
{
    return $this->hasOne(UserProfile::class, 'IdGeneral', 'IdGeneral');
}
    protected function fechaAlta(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value ? Carbon::parse($value)->format('Ymd H:i:s') : null,
        );
    }
}
