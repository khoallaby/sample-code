<?php
/*
 * This is a controller for handling user logins
 * It creates a token, and is emailed to user, of which they need to input the token in order to login successfully
 * It also logs every login attempt
 *
 */

use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\Websitemail;
use Auth;
use DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;

class LoginController extends \App\Http\Controllers\Controller
{
    public function login()
    {
        if(\App\Http\Controllers\auth()->check())
            return $this->redirectBasedOnRole();
        return \App\Http\Controllers\view('login.login');
    }


    public function randomizedString()
    {
        $length = 6;
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function login_submit(Request $request)
    {
        $email = $request->email;
        $user = User::where('email', $email)->first();
        if(!is_object($user)){
            return \App\Http\Controllers\redirect()->route('login')->with('error', 'Login failed, please try again.');
        }
        if (!$user->enabled) {
            return \App\Http\Controllers\redirect()->route('login')->with('error', 'Your account is disabled. Please contact the administrator.');
        }

        $randomString = $this->randomizedString();


        $user->token = $randomString;
        $expiryHours = \App\Http\Controllers\config('auth.token_expiry');
        $user->token_expires_at = Carbon::now()->addHours($expiryHours);
        $user->update();

        $message = View::make('emails.email_confirmation', ['randomString' => $randomString, 'expiryHours'=> $expiryHours])->render();
        \Mail::to($request->email)->send(new Websitemail($message, 'Login Confirmation'));

        $encryptedEmail = Crypt::encrypt($request->email);
        return \App\Http\Controllers\redirect()->route('login_verify', ['email' => $encryptedEmail]);
    }

    public function login_verify($email)
    {
        $decryptedEmail = Crypt::decrypt($email);
        return \App\Http\Controllers\view('login.login_verify', ['email' => $decryptedEmail]);
    }

    public function login_verify_submit(Request $request)
    {
        $code = $request->code;
        $email = Crypt::decrypt($request->email);
        $encryptedEmail = Crypt::encrypt($email);

        //expiration token
        $user = User::where('email', $email)
        ->where('token', $code)
        ->where('token_expires_at', '>', Carbon::now())
        ->first();

        if(!is_object($user))
            return \App\Http\Controllers\redirect()->route('login_verify', ['email' => $encryptedEmail])->with('error', 'Invalid token');

        if(Auth::loginUsingId($user->id))
        {
            $user->token= '';
            $user->token_expires_at = null;
            $user->update();
            DB::table('login_logs')->insert([
                'user_id' => $user->id,
                'logged_in_at' => \App\Http\Controllers\now(),
            ]);

            return $this->redirectBasedOnRole();

        }

    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return \App\Http\Controllers\redirect()->route('login')->with('logout', 'You have been successfully logged out');
    }
    public function showLoginLogs()
    {
        $user = \App\Http\Controllers\auth()->user();
        if (!$user->isAdmin()) {
            return \App\Http\Controllers\redirect()->route('home')->with('error', 'You do not have permission to see this page.');
        }

        $logs = \DB::table('login_logs')
        ->join('users', 'users.id', '=', 'login_logs.user_id')
        ->select('users.id', 'users.first_name', 'users.last_name', 'login_logs.logged_in_at')
        ->orderByDesc('login_logs.logged_in_at')
        ->simplePaginate(15);



        foreach ($logs as $log) {
            $log->logged_in_at = \Carbon\Carbon::parse($log->logged_in_at)
                ->timezone('America/New_York')
                ->format('m/d: g:i A');
        }

        return \App\Http\Controllers\view('login.login_logs', compact('logs'));
    }
    public function loginLogs($userId) {
        $user = User::find($userId);

        if (!$user) {
            return \App\Http\Controllers\redirect()->route('showLoginLogs')->with('error', 'User not found');
        }

        $logs = $user->loginLogs()
            ->orderByDesc('logged_in_at')
            ->take(10)
            ->get()
            ->map(function($log) {
                return Carbon::parse($log->logged_in_at)->timezone('America/New_York')->format('m/d: g:i A');
            });

        return \App\Http\Controllers\view('login.login_logs_user', compact('logs', 'user'));
    }

    public function redirectBasedOnRole() {
        if(\App\Http\Controllers\auth()->user()->isAdmin() || \App\Http\Controllers\auth()->user()->isCompany()) {
            $redirect = 'company_management';
        } else {
            $redirect = 'companies';
        }

        return \App\Http\Controllers\redirect()->route($redirect);
    }
}
