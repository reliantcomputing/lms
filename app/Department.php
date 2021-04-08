<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = "department";

    public function students()
    {
        return $this->hasMany('App\Student');
    }

    public function books()
    {
        return $this->hasMany('App\Book');
    }
}
