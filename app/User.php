<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notification;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $table = "user";

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function notifications()
    {
        return Notification::where("user_id", $this->id)->where("is_viewed", false)->get();
    }

    public function department()
    {
        $staff = Staff::where("email", $this->email)->first();
        $department = Department::where("id", $staff->department_id)->first();
        return $department;
    }

    public function library()
    {
        $librarian = Librarian::where("email", $this->email)->first();
        $library = Library::where("id", $librarian->library_id)->first();
        return $library;
    }

}
