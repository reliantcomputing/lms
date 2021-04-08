<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Department;
use App\Librarian;

class BookReservation extends Model
{
    protected $table = "book_reservation";

    public function book()
    {
        return $this->belongsTo('App\Book');
    }

    public function student()
    {
        return Student::where("student_number", $this->student_number)->first();
    }
    public function department()
    {
        return Department::where("id", $this->department_id)->first();
    }
    public function librarian()
    {
        return Librarian::where("librarian_number", $this->librarian_number)->first();
    }


}
