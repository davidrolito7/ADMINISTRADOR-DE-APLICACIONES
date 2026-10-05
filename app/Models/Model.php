<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Carbon;

abstract class Model extends EloquentModel
{
    // SQL Server (Spanish locale) interprets YYYY-MM-DD as DMY, so we use
    // the unseparated ISO format YYYYMMDD which is always unambiguous.
    protected $dateFormat = 'Y-m-d H:i:s';

    public function fromDateTime($value): ?string
    {
        return empty($value) ? null : $this->asDateTime($value)->format('Ymd H:i:s');
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return Carbon::instance($date)
            ->timezone(config('app.timezone'))
            ->format('Y-m-d H:i:s.u');
    }
}
