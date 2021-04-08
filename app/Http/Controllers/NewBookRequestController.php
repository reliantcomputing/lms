<?php

namespace App\Http\Controllers;

use App\NewBookRequest;
use App\Staff;
use App\Librarian;
use App\Notification;
use App\User;
use App\Department;
use App\Book;
use App\Exports\NewBookRequestsExport;
use Auth;
use PDF;
use Excel;
use Illuminate\Http\Request;

class NewBookRequestController extends Controller
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
    
    public function index()
    {
        if (Auth::user()->role->name == "ROLE_DEPARTMENT" || Auth::user()->role->name == "ROLE_SUPER_DEPARTMENT") {
            
             $userEmail = Auth::user()->email;
             $staff = Staff::where("email", $userEmail)->first();
             $newBookRequests = NewBookRequest::where("department_id", $staff->department_id)->get();
             return view("newBookRequests.index", ["newBookRequests" => $newBookRequests]);
        }
        $newBookRequests = NewBookRequest::all();
        return view("newBookRequests.index", ["newBookRequests" => $newBookRequests]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("newBookRequests.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     //new book ordered
    public function store(Request $request)
    {
        $this->validate($request, [
            "title" => "required",
            "quantity" => "required",
            "isbn_number" => "required|min:13|max:13",
            "author" => "required",
            "edition" => "required",
            "place_of_publication" => "required"
        ]);

        $userEmail = Auth::user()->email;
        $staff = Staff::where("email", $userEmail)->first();
        $librarians = Librarian::all();
        $newBookRequest = new NewBookRequest;
        $newBookRequest->title = $request->title;
        $newBookRequest->staff_number = $staff->staff_number;
        $newBookRequest->quantity = $request->quantity;
        $newBookRequest->isbn_number = $request->isbn_number;
        $newBookRequest->author = $request->author;
        $newBookRequest->status = "Pending";
        $newBookRequest->edition = $request->edition;
        $newBookRequest->place_of_publication = $request->place_of_publication;
        $newBookRequest->department_id = $staff->department_id;

        $department = Department::where("id", $staff->department_id)->first();

        if (NewBookRequest::where("isbn_number", $request->isbn_number)->first()) {
            return redirect()->back()->with("error", "Book with ISBN Number $request->isbn_number already exist.")->withInput();
        }

        $initial = $staff->name[0];
        $newBookRequest->save();

        foreach($librarians as $librarian){
            $notification = new Notification;
            $user = User::where("email", $librarian->email)->first();
            $notification->title = "New book request";
            $notification->body = "
                <b>Book request by: </b> $staff->staff_number, $staff->surname $initial, <b>Requested on: </b>$newBookRequest->created_at<hr>
                <b>Title: </b> $newBookRequest->title <br>
                <b>Quantity: </b> $request->quantity <br>
                <b>ISBN Number: </b> $request->isbn_number <br>
                <b>Author: </b> $request->author <br> 
                <b>Place of publication: </b> $request->place_of_publication <br>
                <b>Edition: </b> $request->edition<small>th</small> <br>
                <b>Department: </b> $department->name <br>
            ";
    
            $notification->user_id = $user->id;
            $notification->save();
        }

        return redirect()->back()->with("success", "New book request created successfully.");
    }

    //new book request rejected by the library
    public function libraryRejectNewBookRequest($id)
    {
        $newBookRequest = NewBookRequest::where("id", $id)->first();

        if (!$newBookRequest) {
            return redirect()->back()->with("error", "New book request you're trying to reject already exist.");
        }

        $principal= Auth::user();

        $department = Department::where("id", $newBookRequest->department_id)->first();
        $staffs = Staff::where("department_id", $department->id)->get();
        $librarian = Librarian::where("email", $principal->email)->first();

        $initial = $librarian->name[0];

        $newBookRequest->library_rejected = true;
        $newBookRequest->status = "Library rejected";
        $newBookRequest->update();

        foreach($staffs as $staff){
            $notification = new Notification;
            $user = User::where("email", $staff->email)->first();
            $notification->title = "Library rejected new book request.";
            $notification->body = "
                <b>New book request rejected by: </b> $librarian->librarian_number, $librarian->surname $initial, <b>Requested on: </b>$newBookRequest->created_at<hr>
                <b>Quantity: </b> $newBookRequest->quantity <br>
                <b>ISBN Number: </b> $newBookRequest->isbn_number <br>
                <b>Author: </b> $newBookRequest->author <br> 
                <b>Place of publication: </b> $newBookRequest->place_of_publication <br>
                <b>Edition: </b> $newBookRequest->edition<small>th</small><br>
                <b>Department: </b> $department->name <br>
            ";
    
            $notification->user_id = $user->id;
            $notification->save();
        }

        return redirect()->back()->with("success", "New book request rejected successfully.");
    }

    //price updated
    public function libraryUpdatePrice(Request $request, $id)
    {
        $newBookRequest = NewBookRequest::where("id", $id)->first();

        if (!$newBookRequest) {
            return redirect()->back()->with("error", "New book request you're trying to reject already exist.");
        }

        $principal= Auth::user()->email;

        $department = Department::where("id", $newBookRequest->department_id)->first();
        $staffs = Staff::where("department_id", $department->id)->get();
        $librarian = Librarian::where("email", $principal)->first();

        $initial = $librarian->name[0];

        $newBookRequest->library_accepted= true;
        $newBookRequest->status = "Price notified";
        $newBookRequest->price = $request->price;
        $newBookRequest->update();

        $totalCost = $newBookRequest->price * $newBookRequest->quantity;

        foreach($staffs as $staff){
            $notification = new Notification;
            $user = User::where("email", $staff->email)->first();
            $notification->title = "New book price notification";
            $notification->body = "
                <b>Notified by: </b> $librarian->librarian_number, $librarian->surname $initial, <b>Requested on: </b>$newBookRequest->created_at<hr>
                <b>Quantity: </b> $newBookRequest->quantity <br>
                <b>ISBN Number: </b> $newBookRequest->isbn_number <br>
                <b>Author: </b> $newBookRequest->author <br> 
                <b>Place of publication: </b> $newBookRequest->place_of_publication <br>
                <b>Edition: </b> $newBookRequest->edition<small>th</small> <br>
                <b>Price: </b> $request->price <br>
                <b>Total Price: </b> $totalCost <br>
                <b>Department: </b> $department->name <br>
            ";
    
            $notification->user_id = $user->id;
            $notification->save();
        }

        return redirect()->back()->with("success", "The department is notified of the price successfully.");
    }

    //department rejected
    public function departmentRejectNewBookRequest($id)
    {
        $newBookRequest = NewBookRequest::where("id", $id)->first();

        if (!$newBookRequest) {
            return redirect()->back()->with("error", "New book request you're trying to reject does exist exist.");
        }
        //get the user(Staff)
        $principal = Auth::user();
        $staff = Staff::where("email", $principal->email)->first();

        //department
        $department = Department::where("id", $newBookRequest->department_id)->first();
        $librarians = Librarian::all();

        $initial = $staff->name[0];

        $newBookRequest->department_rejected= true;
        $newBookRequest->status = "Department Rejected";
        $newBookRequest->update();

        foreach($librarians as $librarian){
            $notification = new Notification;
            $user = User::where("email", $librarian->email)->first();
            $notification->title = "Department rejected new book request offer.";
            $notification->body = "
                <b>Rejected by: </b> $staff->staff_number, $staff->surname $initial, <b>Requested on: </b>$newBookRequest->created_at<hr>
                <b>Quantity: </b> $newBookRequest->quantity <br>
                <b>ISBN Number: </b> $newBookRequest->isbn_number <br>
                <b>Author: </b> $newBookRequest->author <br> 
                <b>Place of publication: </b> $newBookRequest->place_of_publication <br>
                <b>Price: </b> $newBookRequest->price <br>
                <b>Edition: </b> $newBookRequest->edition<small>th</small> <br>
                <b>Department: </b> $department->name <br>
            ";
    
            $notification->user_id = $user->id;
            $notification->save();
        }

        return redirect()->back()->with("success", "New book order rejected successfully.");
    }

    //department approve
    public function departmentAcceptNewBookRequest($id)
    {
        $newBookRequest = NewBookRequest::where("id", $id)->first();

        if (!$newBookRequest) {
            return redirect()->back()->with("error", "New book request you're trying to reject does not exist.");
        }
        //get the user(Staff)
        $principal = Auth::user();
        $staff = Staff::where("email", $principal->email)->first();

        //department
        $department = Department::where("id", $newBookRequest->department_id)->first();
        $librarians = Librarian::all();

        $initial = $staff->name[0];

        $newBookRequest->department_accepted= true;
        $newBookRequest->status = "Department approved";
        $newBookRequest->update();

        foreach($librarians as $librarian){
            $notification = new Notification;
            $user = User::where("email", $librarian->email)->first();
            $notification->title = "Department approved new book request.";
            $notification->body = "
                <b>Approved by: </b> $staff->staff_number, $staff->surname $initial, <b>Requested on: </b>$newBookRequest->created_at<hr>
                <b>Quantity: </b> $newBookRequest->quantity <br>
                <b>ISBN Number: </b> $newBookRequest->isbn_number <br>
                <b>Author: </b> $newBookRequest->author <br> 
                <b>Place of publication: </b> $newBookRequest->place_of_publication <br>
                <b>Price: </b> $newBookRequest->price <br>
                <b>Edition: </b> $newBookRequest->edition<small>th</small> <br>
                <b>Department: </b> $department->name <br>
            ";
    
            $notification->user_id = $user->id;
            $notification->save();
        }

        return redirect()->back()->with("success", "New book request accepted successfully.");
    }

    //Process new book request
    public function processNewBookRequest($id)
    {
        $newBookRequest = NewBookRequest::where("id", $id)->first();

        if (!$newBookRequest) {
            return redirect()->back()->with("error", "New book request you're trying to process does already exist.");
        }

        $principal= Auth::user();

        $department = Department::where("id", $newBookRequest->department_id)->first();
        $staffs = Staff::where("department_id", $department->id)->get();
        $librarian = Librarian::where("email", $principal->email)->first();

        $initial = $librarian->name[0];

        $newBookRequest->status = "Processed";
        $newBookRequest->is_processed = true;
        $newBookRequest->update();
        $totalCost = $newBookRequest->price * $newBookRequest->quantity;

        $department->budget = $department->budget - $totalCost;
        $department->update();

        foreach($staffs as $staff){
            $notification = new Notification;
            $user = User::where("email", $staff->email)->first();
            $notification->title = "New book request processed.";
            $notification->body = "
                <b>Processed by: </b> $librarian->librarian_number, $librarian->surname $initial, <b>Requested on: </b>$newBookRequest->created_at<hr>
                <b>Quantity: </b> $newBookRequest->quantity <br>
                <b>ISBN Number: </b> $newBookRequest->isbn_number <br>
                <b>Author: </b> $newBookRequest->author <br> 
                <b>Place of publication: </b> $newBookRequest->place_of_publication <br>
                <b>Price: </b> $newBookRequest->price <br>
                <b>Total cost: </b> $totalCost <br>
                <b>Edition: </b> $newBookRequest->edition<small>th</small><br>
                <b>Department: </b> $department->name <br>
            ";
    
            $notification->user_id = $user->id;
            $notification->save();
        }

        //create a new book
        $book = new Book;
        $book->title = $newBookRequest->title;
        $book->isbn_number = $newBookRequest->isbn_number;
        $book->author = $newBookRequest->author;
        $book->place_of_publication = $newBookRequest->place_of_publication;
        $book->edition = $newBookRequest->edition;
        $book->stock_price = $newBookRequest->price;
        $book->count = $newBookRequest->quantity;
        $book->department_id = $department->id;
        $book->library_id = 1;

        $book->sell_price = ($book->stock_price*(30/100.0))+$book->stock_price;
        $book->save();

        return redirect()->back()->with("success", "New book processed successfully.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\NewBooknewBookRequest  $newBookRequest
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $newBookRequest = NewBookRequest::where("id", $id)->first();
        if (!$newBookRequest) {
            return redirect()->back()->with("error", "New book request not found.");
        }

        if (Auth::user()->role->name == "ROLE_LIBRARY" || Auth::user()->role->name == "ROLE_SUPER_LIBRARY") {
            $userEmail = Auth::user()->email;
            $librarian = Librarian::where("email", $userEmail)->first();
            $newBookRequest->librarian_number = $librarian->librarian_number;
            $newBookRequest->update();
        }

        return view("newBookRequests.show", ["newBookRequest" => $newBookRequest]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\NewBookRequest  $newBookRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(NewBookRequest $newBookRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\NewBookRequest  $newBookRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NewBookRequest $newBookRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\NewBookRequest  $newBookRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(NewBookRequest $newBookRequest)
    {
        //
    }

    public function export(Request $request)
    {
        $orders = collect();
        $newBookRequests = NewBookRequest::all();
        $title = null;

        if (Auth::user()->role->name == "ROLE_DEPARTMENT" || Auth::user()->role->name == "ROLE_SUPER_DEPARTMENT") {
            
            $user = Auth::user();
            $staff = Staff::where("email", $user->email)->first();
            $department = Department::where("id", $staff->department_id)->first();

            if ($request->sort == "department_accepted") {
                $title = "Department accepted new book requests";
                $orders = NewBookRequest::where("department_id", $department->id)->where("department_accepted", true)->get();
            }
            if ($request->sort == "department_rejected") {
                $title = "Department rejected new book requests";
                $orders = NewBookRequest::where("department_id", $department->id)->where("department_rejected", true)->get();
            }
            if ($request->sort == "library_accepted") {
                $title = "Library accepted new book requests";
                $orders = NewBookRequest::where("department_id", $department->id)->where("library_accepted", true)->get();
            }
            if ($request->sort == "library_rejected") {
                $title = "Library rejected new book requests";
                $orders = NewBookRequest::where("department_id", $department->id)->where("library_rejected", true)->get();
            }
            if ($request->sort == "all") {
                $title = "All book requests";
                $orders = NewBookRequest::where("department_id", $department->id)->get();
            }

            if ($request->sort == "all") {
                $title = "Processed book requests";
                $orders = NewBookRequest::where("department_id", $department->id)->where("is_processed", true)->get();
            }
        }else{
            if ($request->sort == "department_rejected") {
                $title = "Department rejected new book requests";
                $orders = NewBookRequest::where("department_rejected", true)->get();
            }
            if ($request->sort == "department_accepted") {
                $title = "Department accepted new book requests";
                $orders = NewBookRequest::where("department_accepted", true)->get();
            }
            if ($request->sort == "library_accepted") {
                $title = "Library approved new book requests";
                $orders = NewBookRequest::where("library_accepted", true)->get();
            }
            if ($request->sort == "library_rejected") {
                $title = "Library accepted new book requests";
                $orders = NewBookRequest::where("library_rejected", true)->get();
            }
            if ($request->sort == "all") {
                $title = "All book requests";
                $orders = NewBookRequest::all();
            }
            if ($request->sort == "processed") {
                $title = "Processed new book requests";
                $orders = NewBookRequest::where("is_processed", true)->get();
            }
        }
        
        if ($request->type == "PDF") {
            $pdf = PDF::loadView("newBookRequests.print", ["orders"=>$orders, 'title' => $title]);
            return $pdf->download("new_book_request_report.pdf");
        }else{
            return Excel::download(new NewBookRequestsExport($title, $orders), 'new_book_request_report.xlsx');
        }
        
    }
}
