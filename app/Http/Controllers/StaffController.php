<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Staff;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Hash;
use App\Department;
use Auth;

class StaffController extends Controller
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
        $user = Auth::user();

        $staff = Staff::where("email", $user->email)->first();

        $staffs = Staff::where("department_id", $staff->department_id)->get();

        return view("staffs.index", ["staffs"=>$staffs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("staffs.create");
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
            "staff_number"=>"required|unique:Staff|min:9|max:9",
            "first_name" => "required|min:3",
            "last_name" => "required|min:3",
            "email" => "required|unique:Staff|email",
        ]);

        $user = new User;

        if($request->staff_number < 0){
            return redirect()->back()->with("error", "Stuff number cannot be negative.")->withInput();
       }


        if (Role::where("name", "ROLE_DEPARTMENT")->first()) {
            $role = Role::where("name", "ROLE_DEPARTMENT")->first();
            $user->role_id = $role->id;
        }else{
            $role = new Role;
            $role->name = "ROLE_DEPARTMENT";
            $role->save();
            $user->role_id = $role->id;
        }

        $staffInstance = new Staff;
        $oldUser = Auth::user();
        $staff = Staff::where("email", $oldUser->email)->first();
        $department = Department::where("id", $staff->department_id)->first();


        $staffInstance->staff_number = $request->staff_number;
        $staffInstance->name = $request->first_name;
        $staffInstance->surname = $request->last_name;
        $staffInstance->email = $request->email;
        $staffInstance->department_id = $staff->department_id;

        $pass = rand(000000000, 99999999);

        $user->email = $staffInstance->email;
        $user->password = Hash::make($pass);

        $staffInstance->save();
        $user->save();

        return redirect()->route('staff')->with("success", "Staff added successfully. pass is: $pass and username is: $user->email");
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
        $staffInstance = Staff::where("staff_number", $id)->first();

        if (!$staffInstance) {
           return redirect()->back()->with("error", "The staff you're trying to edit does not exist");
        }

        return view("staffs.edit", ["staff"=>$staffInstance]);
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
            "first_name" => "required|min:3",
            "last_name" => "required|min:3"
        ]);

        $staffInstance = Staff::where("staff_number", $id)->first();

        if (!$staffInstance) {
           return redirect()->back()->with("error", "Staff you're trying to update is not found");
        }

        $staffInstance->name = $request->first_name;
        $staffInstance->surname = $request->last_name;
        $staffInstance->update();

        return redirect()->route('staff')->with("success", "Staff updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $staffInstance = Staff::where("staff_number", $id)->first();

        if (!$staffInstance) {
           return redirect()->back()->with("error", "Staff not found");
        }

        $user = User::where("email", $staffInstance->email)->first();

        if ($user) {
            $user->delete();
            $staffInstance->delete();
        }
        return redirect()->route('staff')->with("success", "Staff deleted successfully.");
    }
}
