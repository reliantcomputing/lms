<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Student;
use App\Department;
use App\Book;
use App\User;
use App\Role;
use App\Staff;
use Auth;

class StudentController extends Controller
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
        $userEmail = Auth::user()->email;
        $staff = Staff::where("email", $userEmail)->first();
        $department = Department::where("id",$staff->department_id)->first();
        $students = Student::where("department_id", $department->id)->get();
        return view("students.index", ["students"=>$students]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("students.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "first_name" => "required",
            "last_name" => "required",
            "email" => "required|email|unique:Student",
            "student_number" => "required|min:9|max:9|unique:Student"
        ]);

        $user = Auth::user();
        $staff = Staff::where("email", $user->email)->first();

        $department = Department::where("id", $staff->department_id)->first();

        $student = new Student;

        $student->name = $request->first_name;
        $student->surname = $request->last_name;
        $student->email = $request->email;
        $student->department_id = $department->id;
        $student->student_number = $request->student_number;

        if ($student->student_number < 0) {
            return redirect()->back()->with("error", "Student number cannot be less than 0.");
        }

        if (Student::where("email", $student->email)->first()) {
           return redirect()->back()->with("error", "Student with email $student->email already exist.");
        }

        if (Student::where("student_number", $student->student_number)->first()) {
            return redirect()->back()->with("error", "Student with student number $student->student_number aready exist!");
        }

        $student->save();

        return redirect()->route("students")->with("success", "Student added successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Student::where("student_number", $id)->first()) {
            return redirect()->back()->with("error", "Student not found!");
        }

        $student = Student::where("student_number", $id)->first();

        $books = Book::all();

        return view("students.show", ["student"=>$student, "books" => $books]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Student::where("student_number", $id)->first()) {
            return redirect()->back()->with("error", "Student not found!");
        }

        $student = Student::where("student_number", $id)->first();

        return view("students.edit", ["student"=>$student]);
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
        $this->validate($request, [
            "first_name" => "required",
            "last_name" => "required",
        ]);

        if (!Student::where("student_number", $id)->first()) {
            return redirect()->back()->with("error", "Student not found!");
        }

        $student = Student::where("student_number", $id)->first();

        $student->name = $request->first_name;
        $student->surname = $request->last_name;

        $student->update();

        return redirect()->route("students")->with("success", "Student updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Student::where("student_number", $id)->first()) {
            return redirect()->back()->with("error", "Student not found!");
        }

        $student = Student::where("student_number", $id)->first();
        $student->delete();

        return redirect()->back()->with("success", "Student deleted successfully!");
    }

    public function profile()
    {

        $userEmail = Auth::user()->email;

        if (Auth::user()->role->name == "ROLE_DEPARTMENT") {
            $department = Department::where("email", $userEmail)->first();
            return view("auth.profile", ["department" => $department]);
        }else{
            $student = Student::where("email", $userEmail)->first();
            return view("auth.profile", ["student" => $student]);
        }
    }

    public function updateProfile(Request $request)
    {
        $userEmail = Auth::user()->email;
        if ($request->password != null || $request->password != "") {
            if (Auth::user()->role->name == "ROLE_STUDENT") {
                $this->validate($request, [
                    "name" => "required",
                    "surname" => "required",
                    'password' => "min:8|max:20|confirmed"
                ]);

                $student = Student::where("email", $userEmail)->first();
                $student->name = $request->name;
                $student->surname = $request->surname;
                $student->update();

                $user = User::where("email", $student->email)->first();
                $user->password = Hash::make($request->password);
                $user->update();
                return redirect()->back()->with("success", "profile updated successfully");
            }else{
                $this->validate($request, [
                    "name" => "required",
                    'password' => "min:8|max:20|confirmed"
                ]);

                $department = Department::where("email", $userEmail)->first();
                $department->name = $request->name;
                $department->update();

                $user = User::where("email", $department->email)->first();
                $user->password = Hash::make($request->password);
                $user->update();
                return redirect()->back()->with("success", "profile updated successfully");
            }
        }else{
            if (Auth::user()->role->name == "ROLE_STUDENT") {
                $this->validate($request, [
                    "name" => "required",
                    "surname" => "required",
                ]);
                $student = Student::where("email", $userEmail)->first();
                $student->name = $request->name;
                $student->surname = $request->surname;
                $student->update();
                return redirect()->back()->with("success", "profile updated successfully");
            }else{
                $this->validate($request, [
                    "name" => "required",
                ]);
                $department = Department::where("email", $userEmail)->first();
                $department->name = $request->name;
                $department->update();
                return redirect()->back()->with("success", "profile updated successfully");
            }
        }
    }
}
