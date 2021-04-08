<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Student;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\PasswordReset;
use App\Department;
use App\Library;
use App\Staff;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    

    public function __construct()
    {
        $userInstance    = new User;
        $roleInstance    = new Role;

        $email = "mangementlibrary@gmail.com";
        $password = "adminpass";
        $role = "ROLE_SUPER_LIBRARY";

        if(Library::all()->count() == 0){
            $library = new Library;
            $library->email = $email;
            $library->save();
        }

        if (!User::where("email", $email)->first()) {
            $roleInstance->name = $role;
            $roleInstance->save();

            $userInstance->role_id = $roleInstance->id;
            $userInstance->email    = $email;
            $userInstance->password = Hash::make($password);
            $userInstance->save();
        }        
    }

    public function passwordResetPage()
    {
        return view("auth.enter-email");
    }

    public function processEmail(Request $request)
    {
        $this->validate($request, [
            'email'=>'required|email'
        ]);
        if (!User::where("email", $request->email)->first()) {
            return redirect()->back()->with("error", "User with email $request->email does not exist.")->withInput();
        }
        return redirect()->route("send-question", $request->email)->with("success", "Please enter your security answer, to finish resetting password.");
    }

    public function sendQuestion($email)
    {
        if (!User::where("email", $email)->first()) {
            return redirect()->back()->with("error", "User with email $email does not exist.")->withInput();
        }
        $passwordReset = PasswordReset::where("email", $email)->first();
        return view("auth.send-question", ["passwordReset"=>$passwordReset]);
    }

    public function processQuestion(Request $request)
    {
        $this->validate($request, [
            "security_answer"=>'required'
        ]);

        $passwordReset = PasswordReset::where("email", $request->email)->first();
        $user = User::where("email", $request->email)->first();

        if ($request->security_answer != $passwordReset->answer) {
            return redirect()->back()->with("error", "Wrong answer.")->withInput();
        }
        $num = rand(00000000,99999999);
        $password = Hash::make(rand($num));
        $user->password = $password;
        $user->update();
        return redirect()->back()->with("success", "Your new password is $num, please login and change it.")->withInput();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('welcome');
    }

    public function createDepartment()
    {
        return view("departments.create");
    }

    public function createStudent()
    {
        return view("students.register");
    }

    public function saveDepartment(Request $request){
        $this->validate(
            $request,
            [
                'department_name'=>'required',
                'first_name'=>'required',
                'staff_number'=>'required|min:9|max:9',
                'surname'=>'required',
                'email' => 'required|email',
                'password' => 'required|confirmed',
                'security_question' => "required",
                'security_answer' => 'required' 
            ]
        );

        if ($request->staff_number < 0) {
            return redirect()->back()->with("error", "Staff number should be negative.")->withInput();
        }

        if (User::where("email", $request->email)->first()) {
            return redirect()->back()->with("error", "User with email $request->email already exist!");
        }

        if (Department::where("name", $request->department_name)) {
            return redirect()->back()->with("error", "Department already exist.")->withInput();
        }
        

        $department = new Department;
        $staff = new Staff;
        $user = new User;

        $department->name = $request->department_name;
        $department->email = $request->email;
        $department->budget = rand(50000, 100000);

        $staff->name = $request->first_name;
        $staff->surname = $request->surname;
        $staff->staff_number = $request->staff_number;
        $staff->email = $request->email;

        $user->email = $request->email;
        
        if (Role::where("name", "ROLE_SUPER_DEPARTMENT")->first()) {
            $role = Role::where("name", "ROLE_SUPER_DEPARTMENT")->first();
            $user->role_id = $role->id;
        }else{
            $role = new Role;
            $role->name = "ROLE_SUPER_DEPARTMENT";
            $role->save();
            $user->role_id = $role->id;
        }
        
        $user->password = Hash::make($request->password);
        $department->save();
        $staff->department_id = $department->id;
        $staff->save();
        $user->save();

        $passwordReset = new PasswordReset;
        $passwordReset->question = $request->security_question;
        $passwordReset->answer = $request->security_answer;
        $passwordReset->email = $user->email;
        $passwordReset->save();
        Auth::login($user);
        return redirect()->route("dashboard")->with("success" , "Registered successfully, Welcome $request->first_name");
        
    }

    public function saveStudent(Request $request){
        $this->validate(
            $request,
            [
                'student_number'=>'required|min:9|max:9',
                'password' => 'required|confirmed',
                'security_question' => "required",
                'security_answer' => 'required' 
            ]
        );

        if ($request->student_number < 0) {
            return redirect()->back()->with("error", "Staff number should be negative.")->withInput();
        }
        
        $student = Student::where("student_number", $request->student_number)->first();

        if (!$student) {
            return redirect()->back()->with("error", "Student with student number $request->student_number does not belong to any department.");
        }

        if (User::where("email", $student->email)->first()) {
            return redirect()->back()->with("error", "User already exist, please try resetting your password if you forgot it.");
        }

        $user = new User;

        $user->email = $student->email;
        
        if (Role::where("name", "ROLE_STUDENT")->first()) {
            $role = Role::where("name", "ROLE_STUDENT")->first();
            $user->role_id = $role->id;
        }else{
            $role = new Role;
            $role->name = "ROLE_STUDENT";
            $role->save();
            $user->role_id = $role->id;
        }
        $passwordReset = new PasswordReset;
        $passwordReset->question = $request->security_question;
        $passwordReset->answer = $request->security_answer;
        $passwordReset->email = $user->email;
        $passwordReset->save();
        
        $user->password = Hash::make($request->password);
        $user->save();
        Auth::login($user);
        return redirect()->route("dashboard")->with("success" , "Registered successfully, welcome $student->full_name $student->surname.");
    }


    public function enterEmailPage()
    {
        return view("home.passwordResetPage");
    }

    public function processEmailCheck(Request $request)
    {
        $this->validate($request, ["email"=>"required|email"]);
        $user = User::where("email", $request->email)->first();
    
        if (!$user) {
            return redirect()->back()->with("error", "User with email $request->email does not exist.");
        }else {
            $passwordQuestion = PasswordReset::where("email", $request->email)->first();
            return view("home.passwordResetQuestionPage", ["success"=>"Please answer the security question to reset your password","email"=>$user->email, "passwordQuestion"=>$passwordQuestion]);
        }
        
    }

    public function processSecurityQuestion(Request $request)
    {
        $this->validate($request, ["email"=>"required|email", "security_answer"=>"required"]);
        $user = User::where("email", $request->email)->first();
        if (!$user) {
            return redirect()->back()->with("error", "User with email $request->email does not exist.");
        }else {
            $passwordQuestion = PasswordReset::where("email", $request->email)->first();
            if ($passwordQuestion->security_answer == $request->security_answer) {
                return view("home.passwordResetPage", ["success"=>"You can now enter your new password","email"=>$user->email, "passwordQuestion"=>$passwordQuestion]);
            }else{
                return redirect()->back()->with("error", "Wrong answer is supplied please try again.");
            }
        }
    }

    public function processPasswordReset(Request $request)
    {
        $this->validate($request, ["new_password"=>"required|min:6|max:20", "confirm_password"=>"required|min:6|max:20"]);

        if ($request->new_password != $request->confirm_password) {
            return redirect()->back()->with("error", "New password and confirm password don't match.");
        }

        $user = User::where("email", $request->email)->first();

        if (!$user) {
            return redirect()->back()->with("error", "User with email $request->email does not exist.")->withInput();
        }

        $user->password = Hash::make($request->new_password);
        $user->update();
        return redirect()->route("dashboard")->with("success", "Password changed successfully, you can now login.");
    }
}
