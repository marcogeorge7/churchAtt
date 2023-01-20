<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Requirement extends Model
{
    use SoftDeletes;

    protected $fillable = ["req_name", "activity_id"];

}