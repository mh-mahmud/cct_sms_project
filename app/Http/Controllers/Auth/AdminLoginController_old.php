<?php
namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use DB;
use App\Admin;
use App\Services\Admin\AuditLogService;


class AdminLoginController extends Controller
{
    /**
     * Show the application’s login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.admin_login');
    }
    protected function guard(){
        return Auth::guard('admin');
    }
    
    use AuthenticatesUsers;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard/#/admin/';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
        $this->AuditLogService = new AuditLogService();
    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }

     /**
     * @param Request $request
     * @throws ValidationException
     * this is working for laravel 5.4
     */

    protected function sendFailedLoginResponse(Request $request)
    { 
        return redirect()->back()
            ->withInput($request->only('account_id', 'password'))
            ->withErrors(['login_error' => [trans('auth.login_failed')]]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    { 
        
        $this->validate($request, [
            'account_id'   => 'required',
            'password' => 'required'
        ]);


        $pbxDbName = config('database.pbx_db_name');
        // $user = Admin::selectRaw("account.*, ap.email, IF(ap.timezone='', ap.timezone, ap.timezone) as timezone, ap.name AS name, ap.password")        
        // ->leftJoin($pbxDbName.'.account_profile as ap','account.account_id','ap.account_id')
        // ->where(['ap.account_id' => $request->account_id,'ap.password' => md5($request->password)])
        // ->first();
        $user = Admin::selectRaw("account_profile.*, IF(account_profile.timezone='', account_profile.timezone, account_profile.timezone) AS timezone")        
        ->where(['account_profile.account_id' => $request->account_id,'account_profile.password' => md5($request->password)])
        ->first(); 
        if ($user) {                   
            $user->password = '';
                       
            $userData = array('account_id'=>$user->account_id,'userid'=>'','name'=>$user->name,'userid'=>'','email'=>$user->email);
            Auth::guard('admin')->login($user, $request->has('remember'));
            $this->AuditLogService->createAdminLog($userData, 'L');
            //$this->guard('admin')->login($user, $request->has('remember'));            
            //return redirect()->intended('/admin/dashboard/#/admin');
	    return redirect('/admin/dashboard/#/admin');
        }        
        return back()->withInput($request->only('account_id', 'remember'));

        
        // if (Auth::guard('admin')->attempt(['account_id' => $request->account_id, 'password' => md5($request->password)], $request->get('remember'))) {
            

        //     return redirect()->intended('/admin/dashboard');
        // }
        // return back()->withInput($request->only('email', 'remember'));
        
    }
    /**
     * set user session data after login
     */
    protected function authenticated(Request $request, $user)
    {
        
        //$request->session()->put('loginUser', $user->getOriginal());
    }
}