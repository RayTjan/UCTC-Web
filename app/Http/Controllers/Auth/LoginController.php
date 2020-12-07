<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function login(Request $request){
        $admin = [
            'email'=>$request->email,
            'password'=>$request->password,
            'role_id'=>1,
            'is_login'=>'0',
            'is_active'=>'1',
            'is_verified'=>'1',
        ];
        $creator = [
            'email'=>$request->email,
            'password'=>$request->password,
            'role_id'=>2,
            'is_login'=>'0',
            'is_active'=>'1',
            'is_verified'=>'1',
        ];
        $user = [
            'email'=>$request->email,
            'password'=>$request->password,
            'role_id'=>3,
            'is_login'=>'0',
            'is_active'=>'1',
            'is_verified'=>'1',
        ];

        if (Auth::attempt($admin)) {
            $this->isLogin(Auth::id());
            return redirect()->route('program.index');
        } elseif (Auth::attempt($creator)) {
            $this->isLogin(Auth::id());
            return redirect()->route('program.index');
        } elseif (Auth::attempt($user)) {
            $this->isLogin(Auth::id());
            return redirect()->route('program.index');
        }
        return redirect()->route('login');

    }

    public function logout(Request $request){
        $acc = User::findOrFail(Auth::id());
        $acc->update([
            'is_login' => '0',
        ]);

        $request->session()->invalidate();
        return $this->loggedOut($request) ?: redirect('login');
    }

    private function isLogin(int $id){
        $acc = User::findOrFail($id);
        return $acc->update([
            'is_login' => '1',
        ]);
    }
}
