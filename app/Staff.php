<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $primaryKey = "staff_number";
    protected $table = "staff";

    public function department()
    {
        return $this->belongsTo('App\Department');
    }
}
