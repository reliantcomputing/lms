<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Librarian;
use App\User;
use App\Role;
use Auth;

class LibrarianController extends Controller
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
        $librarians = Librarian::all();

        return view("librarians.index", ["librarians"=>$librarians]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("librarians.create");
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
            "librarian_number"=>"unique:librarian|min:9|max:9",
            "first_name" => "required|min:3",
            "last_name" => "required|min:3",
            "email" => "required|unique:Librarian|email",
        ]);

        $user = new User;
        if($request->librarian_number <= 0){
            return redirect()->back()->with("error", "Librarian number cannot be negative.");
       }


        if (Role::where("name", "ROLE_LIBRARY")->first()) {
            $role = Role::where("name", "ROLE_LIBRARY")->first();
            $user->role_id = $role->id;
        }else{
            $role = new Role;
            $role->name = "ROLE_LIBRARY";
            $role->save();
            $user->role_id = $role->id;
        }

        $librarianInstance = new Librarian;
        $oldUser = Auth::user();


        $librarianInstance->librarian_number = $request->librarian_number;
        $librarianInstance->name = $request->first_name;
        $librarianInstance->surname = $request->last_name;
        $librarianInstance->email = $request->email;
        $librarianInstance->library_id = $oldUser->id;

        $pass = rand(000000000, 99999999);

        $user->email = $librarianInstance->email;
        $user->password = Hash::make($pass);

        $librarianInstance->save();
        $user->save();

        return redirect()->route('librarians')->with("success", "Librarian added successfully. pass is: $pass and username is: $user->email");
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
        $libraryInstance = Librarian::where("librarian_number", $id)->first();

        if (!$libraryInstance) {
           return redirect()->back()->with("error", "Librarian not found");
        }

        return view("librarians.edit", ["library"=>$libraryInstance]);
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

        $libraryInstance = Librarian::where("librarian_number", $id)->first();

        if (!$libraryInstance) {
           return redirect()->back()->with("error", "Librarian not found");
        }

        $libraryInstance->name = $request->first_name;
        $libraryInstance->surname = $request->last_name;
        $libraryInstance->update();

        return redirect()->route('librarians')->with("success", "Librarian updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $libraryInstance = Librarian::where("librarian_number", $id)->first();

        if (!$libraryInstance) {
           return redirect()->back()->with("error", "Librarian not found");
        }

        $user = User::where("email", $libraryInstance->email)->first();

        if ($user) {
            $user->delete();
            $libraryInstance->delete();
        }
        return redirect()->route('librarians')->with("success", "Librarian deleted successfully.");
    }
}
