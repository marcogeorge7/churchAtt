<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ServiceAllocation extends Model
{

    protected $fillable = ['year_id', 'person_id', 'service_id', 'service_title_id', 'is_servant'];

}
