<?php

namespace App\Exports;

use App\BookReservation;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BookReservationsExport implements FromView, ShouldAutoSize
{
    public $title;
    public $bookReservations;
    public function __construct($title, $bookReservations)
    {
        $this->title = $title;
        $this->bookReservations = $bookReservations;
    }

    public function view(): View
    {
        return view('bookReservations.print', [
            'orders' => $this->bookReservations,
            'title' => $this->title,
        ]);
    }
}
