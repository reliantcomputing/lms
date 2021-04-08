<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $primaryKey = "student_number";
    protected $table = "student";

    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    public function bookReservations()
    {
        return $this->hasMany('App\BookReservation');
    }
}
