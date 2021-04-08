<?php

namespace App\Exports;

use App\BookRequest;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BookRequestsExport implements FromView, ShouldAutoSize
{
    public $title;
    public $bookRequests;
    public function __construct($title, $bookRequests)
    {
        $this->title = $title;
        $this->bookRequests = $bookRequests;
    }

    public function view(): View
    {
        return view('bookRequests.print', [
            'orders' => $this->bookRequests,
            'title' => $this->title,
        ]);
    }
}
