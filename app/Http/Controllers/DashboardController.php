<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Librarian;
use App\Book;
use App\BookReservation;
use App\BookRequest;
use App\Department;
use App\Student;
use App\Staff;
use Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        if (Auth::user()->role->name == "ROLE_SUPER_LIBRARY") {

            $librarians = Librarian::all();
            return view("dashboard.index", ["librarians"=>$librarians]);

        }elseif (Auth::user()->role->name == "ROLE_LIBRARY") {

            $books = Book::all();
            $bookReservations = BookReservation::all();
            $bookRequests = BookRequest::all();

            return view("dashboard.index", [
                "books"=>$books,
                "bookReservations"=>$bookReservations,
                "bookRequests" => $bookRequests
            ]);
        }elseif (Auth::user()->role->name == "ROLE_SUPER_DEPARTMENT" || Auth::user()->role->name == "ROLE_DEPARTMENT") {
            
            $userEmail = Auth::user()->email;
            $staff = Staff::where("email", $userEmail)->first();
            $department = Department::where("id", $staff->department_id)->first();

            $books = Book::where("department_id", $department->id)->get();
            $staff = Staff::where("department_id", $department->id)->get();
            $students = Student::where("department_id", $department->id)->get();

            return view("dashboard.index", [
                "books"=>$books,
                "staff"=>$staff,
                "students" => $students
            ]);
        }else{
            $userEmail = Auth::user()->email;
            $student = Student::where("email", $userEmail)->first();
            $department = Department::where("id", $student->department_id)->first();

            $books = Book::where("department_id", $department->id)->get();
            $bookReservations = BookReservation::where("student_number", $student->student_number)->get();

            return view("dashboard.index", [
                "books"=>$books,
                "bookReservations"=>$bookReservations
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        //
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
