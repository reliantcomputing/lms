<?php

namespace App\Exports;

use App\NewBookRequest;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class NewBookRequestsExport implements FromView, ShouldAutoSize
{
    public $title;
    public $newBookRequests;
    public function __construct($title, $newBookRequests)
    {
        $this->title = $title;
        $this->newBookRequests = $newBookRequests;
    }

    public function view(): View
    {
        return view('newBookRequests.print', [
            'orders' => $this->newBookRequests,
            'title' => $this->title,
        ]);
    }
}
