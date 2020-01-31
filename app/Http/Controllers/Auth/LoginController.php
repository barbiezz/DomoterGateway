<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7;
use GuzzleHttp\Client;
use App\DeviceProfile;
use App\Device;
use App\Gateway;
use DB;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public static function sendRequest(){
    
        $client = new Client(['base_uri' => 'http://domoter.test', 'allow_redirects' => true, 'cookies' => true]);
        $redirect = 'http://domoter.test/home';

        $response = $client -> request(
            'GET', 
            $redirect, 
        );
        echo $response->getBody();
    }
}
