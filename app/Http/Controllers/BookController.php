<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Department;
use App\Book;
use App\Staff;
use App\Student;
use App\Librarian;
use App\Notification;
use App\User;
use App\BookRequest;
use Auth;

class BookController extends Controller
{
    public function __construct()
    {
       $this->middleware(["auth"]);
    }

   public function index()
   {
       if (Auth::user()->role->name == "ROLE_STUDENT" || Auth::user()->role->name == "ROLE_DEPARTMENT" || Auth::user()->role->name == "ROLE_SUPER_DEPARTMENT") {
           if (Auth::user()->role->name == "ROLE_STUDENT") {
               $userEmail = Auth::user()->email;
               $student = Student::where("email", $userEmail)->first();
               $books = Book::where("department_id", $student->department_id)->get();
               return view("books.index", ["books" => $books]);
           }
            $userEmail = Auth::user()->email;
            $staff = Staff::where("email", $userEmail)->first();
            $books = Book::where("department_id", $staff->department_id)->get();
            return view("books.index", ["books" => $books]);
       }
       $books = Book::all();
       return view("books.index", ["books" => $books]);
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create()
   {
       $departments = Department::all();
       return view("books.create", ["departments" => $departments]);
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
       $this->validate($request,
           [
               "title" => "required",
               "isbn_number" => "required|min:10|max:13|unique:Book",
               "author" => "required",
               "place_of_publication" => "required",
               "edition" => "required",
               "stock_price" => "required",
               "department" => "required"
           ]
       );

       $book = new Book;

       $userEmail = Auth::user()->email;

       $employer = Librarian::where("email", $userEmail)->first();

       $department = Department::where("id", $request->department)->first();

       $book->title = $request->title;
       $book->isbn_number = $request->isbn_number;
       $book->author = $request->author;
       $book->place_of_publication = $request->place_of_publication;
       $book->edition = $request->edition;
       $book->stock_price = $request->price;
       $book->department_id = $department->id;
       $book->library_id = 1;
       $book->stock_price = $request->stock_price;

       $book->sell_price = ($book->stock_price*(30/100.0))+$book->stock_price;

       

       if ($book->edition < 0) {
           return redirect()->back()->with("error", "Book edition should be less than zero.");
       }
       if ($book->isbn_number < 0) {
           return redirect()->back()->with("error", "ISBN number of copies should be less than zero.");
       }

       if ($book->price < 0) {
           return redirect()->back()->with("error", "Price of copies should be less than zero.");
       }
       $department->budget = $department->budget -  $book->price * $book->count;

       if ($department->buget < 0) {
           return redirect()->back()->with("error", "Insufficient account");
       }
       $book->save();
       $department->update();
   
       return redirect()->route("books")->with("success", "Book added successfully!");
   }

   /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function show($id)
   {
       //
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function edit($id)
   {
       if (!Book::where("id", $id)->first()) {
           return redirect()->back()->with("error", "Book not found");
       }
       $book = Book::where("id", $id)->first();
       $departments = Department::all();
       return view("books.edit", ["departments" => $departments, "book"=>$book]);
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
       $this->validate($request,
           [
               "title" => "required",
               "isbn_number" => "required|min:10|max:13",
               "author" => "required",
               "place_of_publication" => "required",
               "edition" => "required",
               "department" => "required",
               "stock_price" => "required",
           ]
       );

       $book = Book::where("id", $id)->first();

       if($request->isbn_number <= 0){
            return redirect()->back()->with("error", "ISBN cannot be negative.");
       }

       if($request->edition <= 0){
                return redirect()->back()->with("error", "Edition be negative.");
        }

       $book->title = $request->title;
       $book->isbn_number = $request->isbn_number;
       $book->author = $request->author;
       $book->place_of_publication = $request->place_of_publication;
       $book->edition = $request->edition;
       $book->department_id = $request->department;
       $book->stock_price = $request->stock_price;
       $book->sell_price = $request->stock_price + ((30/100)*$request->stock_price);

       $book->update();
       
       return redirect()->route("books")->with("success", "Book updated successfully!");
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function destroy($id)
   {
    if (!Book::where("id", $id)->first()) {
        return redirect()->back()->with("error", "Book not found");
    }

    $book = Book::where("id", $id)->first();
    $book->delete();
    return redirect()->route("books")->with("success", "Book deleted successfully!");
   }

   public function orderPage($id)
   {
        if (!Book::where("id", $id)->first()) {
            return redirect()->back()->with("error", "Book not found");
        }

        $book = Book::where("id", $id)->first();
        return view("books.order", ["book" => $book]);
   }

public function order(Request $request, $id)
{
    $this->validate($request, [
        "number_of_books" => "required"
    ]);

    if (!Book::where("id", $id)->first()) {
        return redirect()->back()->with("error", "Book not found");
    }

    $book = Book::where("id", $id)->first();
    
    if ($request->number_of_books < 0) {
        return redirect()->back()->with("error", "Number of books cannot be less than zero");
    }

    $userEmail = Auth::user()->email;
    $librarian = Librarian::where("email", $userEmail)->first();
    
    $book->count = $book->count + $request->number_of_books;

    $department = Department::where("id", $book->department_id)->first();

    $staff = Staff::where("department_id", $book->department_id)->get();
    $totalCost = $book->stock_price * $book->count;

    $department->bugdet = $department->budget - $totalCost;
    $department->update();
    $initial = $librarian->name[0];
    foreach($staff as $stuff){
        $notification = new Notification;
        $user = User::where("email", $stuff->email)->first();
        $notification->title = "Books ordered";
        $notification->body = "
            <b>$request->number_of_books</b> book(s) ordered by <b>$librarian->librarian_number, $librarian->surname $initial</b> <hr>
            <b>Book title: </b> $book->title <br>
            <b>Book Edition: </b> $book->edition<small>th</small> <br>
            <b>Stock Price: </b> R$book->stock_price <br>
            <b>Sell Price: </b> R$book->sell_price <br>
            <b>ISBN: </b> R$book->isbn_number <br>
            <b>Total Cost: </b> $totalCost<br>
        ";

        $notification->user_id = $user->id;
        $notification->save();
    }

    $book->update();
    return redirect()->route("books")->with("success", "Book(s) ordered successfully.");
}


public function rejectBookOrder($id)
{

    $bookRequest = BookRequest::where("id", $id)->first();

    if (!$bookRequest) {
        return redirect()->back()->with("error", "Book request not found");
    }

    $book = Book::where("id", $bookRequest->book_id)->first();

    $bookRequest = BookRequest::where("id", $request->id)->first();

    $bookRequest->is_rejected = true;
    $bookRequest->update();

    $userEmail = Auth::user()->email;
    $librarian = Librarian::where("email", $userEmail)->first();
    
    $book->count = $book->count + $request->number_of_books;

    $staff = Staff::where("department_id", $book->department_id)->get();
    $totalCost = $book->stock_price * $book->count;
    $initial = $librarian->name[0];
    foreach($staff as $stuff){
        $notification = new Notification;
        $user = User::where("email", $stuff->email)->first();
        $notification->title = "Book request rejected";
        $notification->body = "
            <b>$request->number_of_books</b> book(s) ordered by <b>$librarian->librarian_number, $librarian->surname $initial</b> <hr>
            <b>Book title: </b> $book->title <br>
            <b>Book Edition: </b> $book->edition<small>th</small> <br>
            <b>Stock Price: </b> R$book->stock_price <br>
            <b>Sell Price: </b> R$book->sell_price <br>
            <b>ISBN: </b> R$book->isbn_number <br>
            <b>Total Cost: </b> $totalCost<br>
        ";

        $notification->user_id = $user->id;
        $notification->save();
    }

    $book->update();
    return redirect()->route("books")->with("success", "Book request rejected successfully.");
}



















   public function borrowBookForm($id)
   {
       if (!Book::where("id", $id)->first()) {
           return redirect()->back()->with("error", "Book not found");
       }

       $book = Book::where("id", $id)->first();
       return view("books.borrow_book_form", ["book" => $book]);
   }

   public function borrow(Request $request, $id)
   {
       $this->validate($request, [
           "email" => "required|email"
       ]);
       
       if (!Book::where("id", $id)->first()) {
           return redirect()->back()->with("error", "Book not found");
       }

       $book = Book::where("id", $id)->first();

       if ($book->isAvailable == null) {
           return redirect()->back()->with("error", "Book not available");
       }

       $employer = Employer::where("email", $request->email)->first();
   
       $student = Student::where("email", $request->email)->first();

       if ($employer != null || $student != null) {
           $book->userEmail = $request->email;
           $book->isAvailable = null;
           $book->update(); 
           return redirect()->route("books")->with("success", "Book borrowed successfully");
       }else{
           return redirect()->route("books")->with("error","Student or employee is not part of the department");
       }    

   }

   public function returnBook($id)
   {
       if (!Book::where("id", $id)->first()) {
           return redirect()->back()->with("error", "Book not found");
       }

       $book = Book::where("id", $id)->first();
       $book->userEmail = null;
       $book->isAvailable = "yes";
       $book->update();

       return redirect()->route("books")->with("success", "Book returned successfully");
   }
}
