<?php

namespace Neelkanth\Laravel\Surveillance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveillanceManager extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'surveillance_enabled_at',
        'surveillance_disabled_at',
        'access_blocked_at',
        'access_unblocked_at'
    ];
}
