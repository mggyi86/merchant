<?php

namespace Hsubuu\Merchant\Controllers\MerchantAuth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesUsers,ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/merchant';
    protected $guard = 'merchantuser';
    protected $username = 'merchant_users';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    public function index()
    {
        return response_ok(["message" => "Welcome From HsuBuu Merchant !"]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('merchant_users', 'password');

        $this->validate($request, [
            'merchant_users' => 'required',
            'password' => 'required',
        ]);

        if (auth()->guard('merchant')->attempt(['merchant_users' => $request->input('merchant_users'), 'password' => $request->input('password')]))
        {
             $token = auth()->guard('merchantuser')->getToken();
            return response_ok(compact('token'));
        }else{
            return response()->json(['error'=>['message' => 'Invalid Username & Password.Please try again!']],422);
        }
    }
    
}
