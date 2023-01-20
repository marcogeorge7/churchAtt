<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [];

    public function parent()
    {
        return $this->belongsTo(Service::class, 'parent_id', 'id');
    }

    public function child()
    {
        return $this->hasMany(Service::class, 'parent_id', 'id');
    }

    public function all_subs()
    {
        $data = $this->child;
        foreach ($data as $child)
            $data = $data->merge($child->all_subs());
        return $data;
    }

    public function all_perant()
    {
        $data = $this->parent;
        foreach ($data as $parent)
            $data = $data->merge($parent->all_perant());
        return $data;
    }

    public function members(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(PersonsDatum::class, ServiceAllocation::class, 'service_id', 'person_id', 'id', 'id')->withPivot('year_id');
    }

    public function parentAll()
    {
        $all = Service::with('parent')->get();
        foreach ($all as $item)
        {
            $name = $item->name;
            $parent = $item->parent;
            while($parent != null){
                $name .= " - " . $parent->name;
                $parent = $parent->parent;
            }
            $item->all_parent = $name;
        }
        return $all;
    }
}
