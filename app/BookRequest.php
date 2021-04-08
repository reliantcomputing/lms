<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Book;

class BookRequest extends Model
{
    protected $table = "book_request";

    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    public function book()
    {
        return Book::where("id", $this->book_id)->first();
    }
}
