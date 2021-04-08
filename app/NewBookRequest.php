<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Book;

class NewBookRequest extends Model
{
    protected $table = "new_book_request";

    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    public function book()
    {
        return Book::where("isbn_number", $this->isbn_number)->first();
    }
}
