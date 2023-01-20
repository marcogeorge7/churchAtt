<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PersonsDatum extends Model
{
    use SoftDeletes;

    public function services()
    {
        return $this->belongsToMany(Service::class, ServiceAllocation::class, 'person_id', 'service_id', 'id', 'id')->withPivot('year_id', 'service_title_id', 'is_servant');
    }

    public function alocators()
    {
        return $this->hasMany(ServiceAllocation::class, 'person_id', 'id');
    }
}
