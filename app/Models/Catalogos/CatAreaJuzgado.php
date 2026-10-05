<?php

namespace App\Models\Catalogos;

use App\Models\ADM\Area;
use Illuminate\Database\Eloquent\Model;

class CatAreaJuzgado extends Model
{
    protected $table = 'cat_area_juzgado';
    protected $primaryKey = 'idCatAreaJuzgado';
    public $timestamps = false;

    protected $fillable = [
        'idArea',
        'idJuzgado',
        'activo'
    ];

    public function area()
    {
        return $this->belongsTo(Area::class, 'idArea', 'IdArea');
    }
    public function juzgado()
    {
        return $this->belongsTo(CatJuzgados::class, 'idJuzgado', 'IdCatJuzgado');
    }
}
