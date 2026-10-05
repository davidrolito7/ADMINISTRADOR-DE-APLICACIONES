<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $connection = 'sqlsrv_2';

    protected $table = 'MS_UserProfile';

    protected $primaryKey = 'UserId';

    public $timestamps = false;

    protected $dateFormat = 'Ymd H:i:s';

    protected $fillable = [
        'UserId',
        'UserName',
        'IdGeneral',
        'CreationDate',
        'IsActive',
        'IsAdmin',
        'IsRoot',
        'Token',
        'Password',
        'PasswordQuestion',
        'PasswordAnswer',
        'IsApproved',
        'DigitalCert',
        'Use2FAToken',
    ];

    protected $casts = [
        'CreationDate' => 'datetime',
        'IsActive' => 'boolean',
        'IsAdmin' => 'boolean',
        'IsRoot' => 'boolean',
        'IsApproved' => 'boolean',
        'DigitalCert' => 'boolean',
        'Use2FAToken' => 'boolean',
    ];

    public function usuario()
    {
        return $this->belongsTo(
            Usuario::class,
            'IdGeneral',
            'IdGeneral'
        );
    }
}