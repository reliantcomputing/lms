<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = "book";

    public function library()
    {
        return $this->belongsTo('App\Library');
    }

    public function department()
    {
        return $this->belongsTo('App\Department');
    }
}
