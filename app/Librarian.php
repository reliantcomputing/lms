<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Librarian extends Model
{
    protected $primaryKey = "librarian_number";
    protected $table = "librarian";

    public function library()
    {
        return $this->belongsTo('App\Library');
    }
}
