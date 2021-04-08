<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Staff;
use App\Book;
use App\BookRequest;
use App\Notification;
use App\Librarian;
use App\Department;
use App\User;
use Excel;
use App\Exports\BookRequestsExport;
use PDF;
use Exports\BookRequestsEport;

class BookRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
       $this->middleware(["auth"]);
    }

    public function print(Request $request)
    {
        $orders = collect();
        $title = null;

        if (Auth::user()->role->name == "ROLE_DEPARTMENT" || Auth::user()->role->name == "ROLE_SUPER_DEPARTMENT") {
            
            $user = Auth::user();
            $staff = Staff::where("email", $user->email)->first();
            $department = Department::where("id", $staff->department_id)->first();

            if ($request->sort == "accepted") {
                $title = "Accepted book requests";
                $orders = BookRequest::where("department_id", $department->id)->where("is_accepted", true)->get();
            }
            if ($request->sort == "rejected") {
                $title = "Rejected book requests";
                $orders = BookRequest::where("department_id", $department->id)->where("is_rejected", true)->get();
            }
            if ($request->sort == "all") {
                $title = "All book requests";
                $orders = BookRequest::where("department_id", $department->id)->get();
            }
        }else{
            if ($request->sort == "rejected") {
                $title = "Rejected new book requests";
                $orders = BookRequest::where("is_rejected", true)->get();
            }
            if ($request->sort == "accepted") {
                $title = "Accepted book requests";
                $orders = BookRequest::where("is_accepted", true)->get();
            }
            if ($request->sort == "all") {
                $title = "All book requests";
                $orders = BookRequest::all();
            }
        }
        
        if ($request->type == "PDF") {
            $pdf = PDF::loadView("bookRequests.print", ["orders"=>$orders, 'title' => $title]);
            return $pdf->download("book_request_report.pdf");
        }else{
            return Excel::download(new BookRequestsExport($title, $orders), 'book_request_report.xlsx');
        }
    }
    
    public function index()
    {
        if (Auth::user()->role->name == "ROLE_DEPARTMENT" || Auth::user()->role->name == "ROLE_SUPER_DEPARTMENT") {
            
            $userEmail = Auth::user()->email;
            $staff = Staff::where("email", $userEmail)->first();
            $bookRequests = BookRequest::where("department_id", $staff->department_id)->get();
            return view("bookRequests.index", ["bookRequests" => $bookRequests]);
       }
       $bookRequests = BookRequest::all();
       return view("bookRequests.index", ["bookRequests" => $bookRequests]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userEmail = Auth::user()->email;
        $staff = Staff::where("email", $userEmail)->first();
        $books = Book::where("department_id", $staff->department_id)->get();

        return view("bookRequests.create", ["books"=>$books]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getSelectedBookRequestPage($id)
    {
        $book = Book::where("id", $id)->first();
        if (!$book) {
            return redirect()->back()->with("error", "Book not found");
        }

        return view("bookRequests.selected_book_request", ["book"=>$book]);
    }

    public function selectedBookRequest(Request $request, $id)
    {
        $this->validate($request,[
            'quantity'=>$request->quantity
        ]);
        if ($request->quantity <= 0) {
            return redirect()->back()->with("error", "Quantity cannot be negative.")->withInput();
        }
        $book = Book::where("id", $id)->first();
        if (!$book) {
            return redirect()->back()->with("error", "Book not found");
        }

        $bookRequest = new BookRequest;
        $user = Auth::user();

        $staff = Staff::where("email", $user->email)->first();

        $department = Department::where("id", $staff->department_id)->first();

        $initial = $staff->name[0];

        $totalPrice = $book->stock_price*$request->quantity;
        if ($department->budget - $totalPrice < 0) {
            return redirect()->back()->with("error", "Insufficient funds");
        }

        if($request->quantity <= 0){
            return redirect()->back()->with("error", "Quantity cannot be negative.");
       }

       $department->budget = $department->budget - $totalPrice;
       if ($department->budget < 0) {
           return redirect()->back()->with("Insufficient funds")->withInput();
       }



        $bookRequest->book_id = $id;
        $bookRequest->status = "Pending";
        $bookRequest->department_id = $department->id;
        $bookRequest->number_of_books = $request->quantity;
        $bookRequest->staff_number = $staff->staff_number;
        $librarians = Librarian::all();

        foreach($librarians as $librarian){
            $notification = new Notification;
            $user = User::where("email", $librarian->email)->first();
            $notification->title = "Book request";
            $notification->body = "
                <b>Book request by: </b> $staff->staff_number, $staff->surname $initial, <b>Requested on: </b>$book->created_at<hr>
                <b>Title: </b> $book->title <br>
                <b>Quantity: </b> $request->quantity <br>
                <b>ISBN Number: </b> $book->isbn_number <br>
                <b>Author: </b> $book->author <br> 
                <b>Place of publication: </b> $book->place_of_publication. <br>
                <b>Edition: </b> $book->edition<small>th</small> <br>
                <b>Department: </b> $department->name. <br>
                <b>Sell Price: </b> $book->sell_price. <br>
                <b>Stock Price: </b> $book->stock_price. <br>
                <b>Total Cost: </b> $totalPrice <br>
            ";
    
            $notification->user_id = $user->id;
            $notification->save();
        }

        $bookRequest->save();
        $department->update();
        return redirect()->back()->with("success", "Book requested successfully.");
    }

    public function reject($id)
    {
        $bookRequest = BookRequest::where("id", $id)->first();
        if (!$bookRequest) {
            return redirect()->back()->with("error", "Book not found");
        }

        $bookRequest->is_rejected = true;
        $bookRequest->status = "Rejected";
        $bookRequest->update();

        $book = Book::where("id", $bookRequest->book_id)->first();

        $principal = Auth::user();

        $librarian = Librarian::where("email", $principal->email)->first();

        $department = Department::where("id", $bookRequest->department_id)->first();
        $staffs = Staff::where("department_id", $department->id)->first();

        $initial = $librarian->name[0];

        $totalPrice = $book->stock_price*$bookRequest->number_of_books;
        $department->budget = $department->budget + $totalPrice;

        foreach($staffs as $staff){
            $notification = new Notification;
            $user = User::where("email", $staff->email)->first();
            $notification->title = "Book request rejected";
            $notification->body = "
                <b>Rejected by: </b> $librarian->staff_number, $librarian->surname $initial, <b>Requested on: </b>$book->created_at<hr>
                <b>Title: </b> $book->title <br>
                <b>Quantity: </b> $bookRequest->number_of_books <br>
                <b>ISBN Number: </b> $book->isbn_number <br>
                <b>Author: </b> $book->author <br> 
                <b>Place of publication: </b> $book->place_of_publication. <br>
                <b>Edition: </b> $book->edition<small>th</small> <br>
                <b>Department: </b> $department->name. <br>
                <b>Sell Price: </b> $book->sell_price. <br>
                <b>Stock Price: </b> $book->stock_price. <br>
                <b>Total Cost: </b> $totalPrice <br>
            ";
    
            $notification->user_id = $user->id;
            $notification->save();
        }

        $bookRequest->save();
        $department->update();
        return redirect()->back()->with("success", "Book request approved successfully.");
    }


    public function approve($id)
    {
        $bookRequest = BookRequest::where("id", $id)->first();
        if (!$bookRequest) {
            return redirect()->back()->with("error", "Book not found");
        }

        $bookRequest->is_accepted = true;
        $bookRequest->status = "Approved";
        $bookRequest->update();

        $book = Book::where("id", $bookRequest->book_id)->first();
        $book->number_of_books = $bookRequest->number_of_books;

        $principal = Auth::user();

        $librarian = Librarian::where("email", $principal->email)->first();

        $department = Department::where("id", $bookRequest->department_id)->first();
        $staffs = Staff::where("department_id", $department->id)->first();

        $initial = $librarian->name[0];

        $totalPrice = $book->stock_price*$bookRequest->number_of_books;

        foreach($staffs as $staff){
            $notification = new Notification;
            $user = User::where("email", $staff->email)->first();
            $notification->title = "Book request rejected";
            $notification->body = "
                <b>Rejected by: </b> $librarian->staff_number, $librarian->surname $initial, <b>Requested on: </b>$book->created_at<hr>
                <b>Title: </b> $book->title <br>
                <b>Quantity: </b> $bookRequest->quantity <br>
                <b>ISBN Number: </b> $book->isbn_number <br>
                <b>Author: </b> $book->author <br> 
                <b>Place of publication: </b> $book->place_of_publication. <br>
                <b>Edition: </b> $book->edition<small>th</small> <br>
                <b>Department: </b> $department->name. <br>
                <b>Sell Price: </b> $book->sell_price. <br>
                <b>Stock Price: </b> $book->stock_price. <br>
                <b>Total Cost: </b> $totalPrice <br>
            ";
    
            $notification->user_id = $user->id;
            $notification->save();
        }

        $bookRequest->update();
        $book->update();
        return redirect()->back()->with("success", "Book request approved successfully.");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bookRequest = BookRequest::where("id", $id)->first();
        if (!$bookRequest) {
            return redirect()->back()->with("error", "Book request not found");
        }

        $user = Auth::user();

        if ($user->role->name == "ROLE_LIBRARY") {
            $librarian = Librarian::where("email", $user->email)->first();
            $bookRequest->librarian_number = $librarian->librarian_number;
            $bookRequest->update();
        }

        return view("bookRequests.show", ["bookRequest"=>$bookRequest]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
