<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Session extends Model
{
    use SoftDeletes;

    protected $fillable = ['date', 'activity_id', 'year_id', 'start', 'end', 'service_id'];
}
