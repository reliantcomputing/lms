<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BookReservation;
use App\Librarian;
use App\Student;
use App\Staff;
use App\Notification;
use App\Department;
use App\User;
use App\Book;
use App\Library;
use PDF;
use Excel;
use App\Exports\BookReservationsExport;
use Auth;

class BookReservationController extends Controller
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
        $title = "";

        if ($request->sort == "rejected") {
            $title = "Rejected new book reservation(s)";
            $orders = BookReservation::where("is_rejected", true)->get();
        }
        if ($request->sort == "accepted") {
            $title = "Accepted book reservation(s)";
            $orders = BookReservation::where("is_accepted", true)->get();
        }
        if ($request->sort == "all") {
            $title = "All book reservation(s)";
            $orders = BookReservation::all();
        }

        if ($request->type == "PDF") {
            $pdf = PDF::loadView("bookReservations.print", ["orders"=>$orders, 'title' => $title]);
            return $pdf->download("book_reservation_report.pdf");
        }else{
            return Excel::download(new BookReservationsExport($title, $orders), 'book_reservation_report.xlsx');
        }
    }
    
    public function index()
    {
        if (Auth::user()->role->name == "ROLE_LIBRARY") {
            
            $userEmail = Auth::user()->email;
            $staff = Staff::where("email", $userEmail)->first();
            $bookReservations = BookReservation::all();
            return view("bookReservations.index", ["bookReservations" => $bookReservations]);
       }
       $user = Auth::user();
       $student = Student::where("email", $user->email)->first();
       $bookReservations = BookReservation::where("student_number", $student->student_number)->get();
       return view("bookReservations.index", ["bookReservations" => $bookReservations]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $book = Book::where("id", $id)->get();

        return view("bookReservations.create", ["book"=>$book]);
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

    public function selectedBookReservation($id)
    {
        $book = Book::where("id", $id)->first();
        if (!$book) {
            return redirect()->back()->with("error", "Book not found");
        }

        if ($book <= 0) {
            return redirect()->back()->with("error", "Out of stock, please check us soon.")->withInput();
        }

        $book->count = $book->count - 1;
        $book->update();

        $bookReservation = new BookReservation;
        $user = Auth::user();

        $student = Student::where("email", $user->email)->first();

        $initial = $student->name[0];

        $department = Department::where("id", $student->department_id)->first();

        $bookReservation->book_id = $id;
        $bookReservation->department_id = $department->id;
        $bookReservation->student_number = $student->student_number;
        $bookReservation->status = "Pending";
        $librarians = Librarian::all();

        $bookReservation->save();

        foreach($librarians as $librarian){
            $notification = new Notification;
            $user = User::where("email", $librarian->email)->first();
            $notification->title = "Book Reservation";
            $notification->body = "
                <b>Book request by: </b> $student->student_number, $student->surname $initial, <b>Requested on: </b>$bookReservation->created_at<hr>
                <b>Title: </b> $book->title <br>
                <b>ISBN Number: </b> $book->isbn_number <br>
                <b>Author: </b> $book->author <br> 
                <b>Place of publication: </b> $book->place_of_publication. <br>
                <b>Edition: </b> $book->edition<small>th</small> <br>
                <b>Price: </b> $book->price <br>
                <b>Department: </b> $department->name. <br>
            ";
    
            $notification->user_id = $user->id;
            $notification->save();
        }

        return redirect()->back()->with("success", "Book reserved successfully.");
    }

    public function approveBookReservation($id)
    {

        $bookReservation= BookReservation::where("id", $id)->first();

        if (!$bookReservation) {
            return redirect()->back()->with("error", "Book reservation not found");
        }

        $bookReservation->is_accepted = true;
        $bookReservation->status = "Approved";
        $bookReservation->update();

        $book = Book::where("id", $bookReservation->book_id)->first();
        $department = Department::where("id", $book->department_id)->first();

        $userEmail = Auth::user()->email;
        $librarian = Librarian::where("email", $userEmail)->first();

        $library = Library::where("id", 1)->first();

        $profit = $book->sell_price - $book->stock_price;

        $libraryProfit = (40/100.0)*$profit;
        $departmentProfit = (60/100.0)*$profit; 
        
        $library->balance = $library->balance + $libraryProfit;
        $department->budget = $department->budget + $departmentProfit;
        
        $book->count = $book->count - 1;

        $initial = $librarian->name[0];
        $notification = new Notification;
        $student = Student::where("student_number", $bookReservation->student_number)->first();
        $user = User::where("email", $student->email)->first();
        $notification->title = "Book reservation accepted";
        $notification->body = "
                <b>Accepted by <b>$librarian->librarian_number, $librarian->surname $initial</b> <hr>
                <b>Book title: </b> $book->title <br>
                <b>Book Edition: </b> $book->edition<small>th</small> <br>
                <b>Price: </b> R$book->sell_price <br>
                <b>ISBN: </b> R$book->isbn_number <br>
            ";

        $notification->user_id = $user->id;
        $notification->save();

        $staffs = Staff::where("department_id", $department->id)->get();
        $moneyOut = $book->stock_price - $departmentProfit;
        foreach ($staffs as $staff) {
            $notificationInstance = new Notification;

            $userInstance = User::where("email", $student->email)->first();
            $notificationInstance->title = "Money in";
            $notificationInstance->body = "
                    <b>Book Purchased<hr>
                    <b>Book title: </b> $book->title <br>
                    <b>Book Edition: </b> $book->edition<small>th</small> <br>
                    <b>Book Price: </b> R$book->sell_price <br>
                    <b>Money in: </b> R$moneyOut<br>
                    <b>ISBN: </b> $book->isbn_number <br>
                ";
                $notificationInstance->user_id = $userInstance->id;
                $notificationInstance->save();
        }

        $book->update();
        $department->update();
        $library->update();
        return redirect()->back()->with("success", "Book processed successfully.");
    }


    public function rejectBookReservation($id)
    {

        $bookReservation= BookReservation::where("id", $id)->first();

        if (!$bookReservation) {
            return redirect()->back()->with("error", "Book reservation not found");
        }

        $book = Book::where("id", $bookReservation->book_id)->first();

        $bookReservation->is_rejected = true;
        $bookReservation->status = "Rejected";
        $bookReservation->update();

        $userEmail = Auth::user()->email;
        $librarian = Librarian::where("email", $userEmail)->first();
        
        $book->count = $book->count + 1;

        $initial = $librarian->name[0];
        $notification = new Notification;
        $student = Student::where("student_number", $bookReservation->student_number)->first();
        $user = User::where("email", $student->email)->first();
        $notification->title = "Book reservation rejected";
        $notification->body = "
                <b>Rejected by <b>$librarian->librarian_number, $librarian->surname $initial</b> <hr>
                <b>Book title: </b> $book->title <br>
                <b>Book Edition: </b> $book->edition<small>th</small> <br>
                <b>Price: </b> R$book->sell_price <br>
                <b>ISBN: </b> R$book->isbn_number <br>
            ";

        $notification->user_id = $user->id;
        $notification->save();


        $book->update();
        return redirect()->back()->with("success", "Book reservation rejected successfully.");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bookReservation = BookReservation::where("id", $id)->first();
        if (!$bookReservation) {
            return redirect()->back()->with("error", "Book request not found");
        }

        $user = Auth::user();

        if ($user->role->name == "ROLE_LIBRARY") {
            $librarian = Librarian::where("email", $user->email)->first();
            $bookReservation->librarian_number = $librarian->librarian_number;
            $bookReservation->update();
        }

        return view("bookReservations.show", ["bookReservation"=>$bookReservation]);
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
