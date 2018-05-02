<?php

namespace App\Http\Controllers\Admin;
use App\Admin;
use App\Http\Controllers\Controller;

use App\Mail\AdminResetPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AdminAuth extends Controller
{
    public function login()
    {
       // return 'hello';
        return view('admin.login');
    }
    public function postLogin(Request $request)
    {
        $rememberme = $request->rememberme == 1 ? true:false;
        if(auth()->guard('admin')->attempt(['email'=>$request->email,'password'=>$request->password],$rememberme)) {
            return redirect('admin');
        }else {
//            session()->flash('error',trans('admin.incorrect informations'));
            return redirect('admin/login')->with('error','email or password Incorrect');
        }
    }
    public function logout()
    {
        auth()->guard('admin')->logout();
        return redirect('admin/login');
    }
    public function forgot_password()
    {
        return view('admin.forgot_password');
    }
    public function forgot_password_post(Request $request)
    {
        $admin = Admin::where('email',$request->email)->first();
        if (!empty($admin)){
            $token = app('auth.password.broker')->createToken($admin);
            $data = DB::table('password_resets')->insert([
                'email'=>$admin->email,
                'token'=>$token,
                'created_at'=>Carbon::now(),
            ]);
            dd($token);
         //  Mail::to($admin->email)->send(new AdminResetPassword(['data'=>$data,'token'=>$token]));
            session()->flash('success',trans('admin.the_link_reset_password'));
            return back();
        }
        return back();
    }

    public function reset_password($token)
    {
      $check_token = DB::table('password_resets')->where('token',$token)
                          ->where('created_at','>',Carbon::now()->subHour(2))->first();
        if(!empty($check_token)){
            return view('admin.reset_password',['data'=>$check_token]);
        }else{
            return redirect(aurl('forgot/password'));
        }
    }
    public function reset_password_post(Request $request)
    {
        
    }
}
