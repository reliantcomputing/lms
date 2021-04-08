<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;

class UserController extends Controller
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
    public function changePasswordPage()
    {
        return view("auth.change-password");
    }

    public function storeNewPassword(Request $request)
    {
        $this->validate($request, [
            "current_password" =>"required", 
            "password" => "required|min:8|max:20",
            "password_confirm" => "required"
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with("error", "Your current password does not match with the password you provided. Please try again.");
        }

        if ($request->password != $request->password_confirm) {
            return redirect()->back()->with("error", "New password and password-confirm don't match.");
        }

        $user->password = Hash::make($request->password);
        $user->update();

        return redirect()->back()->with("success", "Password changed successfully.");
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
            return redirect()->back()->with("error", "User with email $email does not exist.")->withInput();
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

}
