<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    protected $table = "library";

    public function books()
    {
        return $this->hasMany('App\Book');
    }
}
