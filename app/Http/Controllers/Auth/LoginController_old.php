<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use DB;
use App\User;
use App\Models\Did;
use App\Services\AuditLogService;

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
    protected $redirectTo = '#/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->AuditLogService = new AuditLogService();
    }
    public function logout(){
        //$this->AuditLogService->createLog(Auth::user(), 'O');
        Auth::logout();
        return redirect('/login');
    }

    /**
     * Check user session
     * @return bool/null
     */
    public function CheckUserSessionData() {
        $prefix="NPABX_";
        $admin_prefix="NPABXAD_";
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
		
		if (isset ( $_SESSION [$admin_prefix.'loggedAdminData'] )) {            
				$data = unserialize ( $_SESSION [$admin_prefix.'loggedAdminData'] );				
				if (isset($data->LoggedIn)&& $data->LoggedIn) {									
				//if(isset($data->account_id) && $data->account_id && isset($data->extn) && $data->extn){
					//if($data->account_id==get('account_id') && $data->extn==get('extn')){
						return true;
					//}
				//}
			}
		}	
		if (isset ( $_SESSION [$prefix.'loggedCallCenterData'] )) {
			$data = unserialize ( $_SESSION [$prefix.'loggedCallCenterData'] );
			if (isset($data->LoggedIn)&& $data->LoggedIn) {
				if(isset($data->account_id) && $data->account_id && isset($data->extn) && $data->extn){
					//if($data->account_id==get('account_id') && $data->extn==get('extn')){
						return true;
					//}
				}
			}
		} 	
		if (isset ( $_SESSION [$prefix.'loggedUserData'] )) {
			$data = unserialize ( $_SESSION [$prefix.'loggedUserData'] );
			if (isset($data->LoggedIn)&& $data->LoggedIn) {
				if(isset($data->account_id) && $data->account_id && isset($data->extn) && $data->extn){
					//if($data->account_id==get('account_id') && $data->extn==get('extn')){
						return true;
					//}
				}
			}
		} 
		
		if (isset ( $_SESSION [$prefix.'loggedExtnUserData'] )) {
			$data = unserialize ( $_SESSION [$prefix.'loggedExtnUserData'] );		
			if (isset($data->LoggedIn)&& $data->LoggedIn) {
				if(isset($data->account_id) && $data->account_id && isset($data->extn) && $data->extn){
					//if($data->account_id==get('account_id') && $data->extn==get('extn')){
						return true;
					//}
				}
			}
		} 
			
		return null;
	}
     /**
     * Check extension
     * @return string
     */
    public function extension()
    {
        $extn  = request()->get('extn');
        $fieldName = 'extn';
        request()->merge([$fieldName => $extn]);

        return $fieldName;
    }

    /**
     * Validate the user login.
     * @param Request $request
     */
    protected function validateLogin(Request $request)
    {        
        $this->validate(
            $request,
            [
                'extn' => 'required|string',
                'account_id' => 'required|string',
                'password' => 'required|string',
            ],
            [
                'extn.required' => 'Extension is required',
                'account_id.required' => 'Account Id is required',
                'password.required' => 'Password is required',
            ]
        );

    }

    /**
     * @param Request $request
     * @throws ValidationException
     * this is working for laravel 5.6
     */
    // protected function sendFailedLoginResponse(Request $request)
    // { 
    //     throw ValidationException::withMessages(
    //         [
    //             'login_error' => [trans('auth.login_failed')],
    //         ]
    //     );
    // }

     /**
     * @param Request $request
     * @throws ValidationException
     * this is working for laravel 5.4
     */

    protected function sendFailedLoginResponse(Request $request)
    { 
        return redirect()->back()
            ->withInput($request->only('account_id', 'extn'))
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
       
        // login from PBX Portal
        $account_id = $request->query('accid');
        if(!empty($account_id)){            
            if($this->CheckUserSessionData()==null){                
                abort(403, 'Unauthorized action.');
            }
            $pbxDbName = config('database.pbx_db_name');
            $user = User::selectRaw("user.*, up.email, IF(up.timezone='', ap.timezone, up.timezone) as timezone, up.password")
            ->leftJoin($pbxDbName.'.user_profile as up','user.userid','up.userid')
            ->leftJoin($pbxDbName.'.account_profile as ap','user.account_id','ap.account_id')
            //->where(['user.account_id' => $request->account_id,'user.extn' => $request->extn,'user.status' => 'A'])
            ->where(['user.account_id' => $request->account_id,'user.extn' => $request->extn,'user.active' => 'Y', 'user.allow_sms' => 'Y'])
            ->first(); 
            if ($user) {
                $user->password = '';
                $did = Did::where('account_id','=',$request->account_id)->where('extn','=',$request->extn)->first();
                if($did){
                    $user->did = $did->did;
                }
                $userData = array('account_id'=>$user->account_id,'userid'=>$user->userid,'department_id'=>$user->department_id,'fname'=>$user->fname,'lname'=>$user->lname,'cname'=>$user->cname);
                $this->guard()->login($user, $request->has('remember'));
                $this->authenticated($request, $user);
                $this->AuditLogService->createLog($userData, 'L');
                return \Redirect::to('/');
            }
            abort(403, 'Unauthorized action.');
        }
        
        $pbxDbName = config('database.pbx_db_name');
        $user = User::selectRaw("user.*, up.email, IF(up.timezone='', ap.timezone, up.timezone) as timezone, up.password")
        ->leftJoin($pbxDbName.'.user_profile as up','user.userid','up.userid')
        ->leftJoin($pbxDbName.'.account_profile as ap','user.account_id','ap.account_id')
        //->where(['user.account_id' => $request->account_id,'user.extn' => $request->extn,'user.status' => 'A'])
        ->where(['user.account_id' => $request->account_id,'user.extn' => $request->extn,'user.active' => 'Y', 'user.allow_sms' => 'Y'])
        ->first();
        if ($user && $user->password == md5($user->userid.$request->password)) {            
            $user->password = '';
            $did = Did::where('account_id','=',$request->account_id)->where('extn','=',$request->extn)->first();            
            if($did){
                $user->did = $did->did;
            }            
            $userData = array('account_id'=>$user->account_id,'userid'=>$user->userid,'department_id'=>$user->department_id,'fname'=>$user->fname,'lname'=>$user->lname,'cname'=>$user->cname);
            $this->guard()->login($user, $request->has('remember'));
            $this->AuditLogService->createLog($userData, 'L');
            return true;
        }
        return false;
        
    }
    /**
     * set user session data after login
     */
    protected function authenticated(Request $request, $user)
    {
        $originalData = $user->getOriginal();$originalData['password']='';
        $originalData['did']=$user->did;
        $request->session()->put('loginUser', $originalData);
    }

}