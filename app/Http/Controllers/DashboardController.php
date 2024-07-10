<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Services\UsersService;
use App\Services\LogService;
use Auth;
use Session;

class DashboardController extends AppController
{
    public $Service;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->account_id = Auth::user() ? Auth::user()->account_id : '';
        $this->Service = new UsersService();
        $this->LogService = new LogService();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.index', [
            'title' => 'Dashboard',
            'search' => '',
            'sub_header' => 'Dashboard',
            'sidebar_menu_active' => 'dashboard',
            'sidebar_submenu_active' => '',
            'sidebar_subsubmenu_active' => '',
        ]);
    }
    public function getDashboardData()
    {         
        $authUser = Session::get('loginUser'); 
        $layoutData['js_plugin'] = $this->getJsPlugin(["JSP_BOOTSTRAP_BOOTBOX"]);
        $layoutData['userType'] = config("dashboard_constant.USER_TYPE");
        $layoutData['userStatus'] = config("dashboard_constant.USER_STATUS");
        $layoutData['logSmsStatus'] = config("dashboard_constant.LOG_SMS_STATUS");
        $layoutData['smsDirection'] = config("dashboard_constant.SMS_DIRECTION");
        $layoutData['title'] = 'Dashboard | '.config("app.name");
        $layoutData['sidebar_menu_active'] = 'dashboard';
        $layoutData['timezone'] =  $authUser['timezone'];
        $layoutData['breadcrumb'] = [
            "links" => [
                [
                    "name" => "Dashboard",
                    "url" => url("#/dashboard"),
                    "icon" => "flaticon-dashboard"
                ]
            ]  
        ];
        $layoutData['unreadLog'] = $this->LogService->getLogUnreadSummary($this->account_id);
        $layoutData['inboundLog'] = $this->LogService->getLogInboundSummary($this->account_id);
        $layoutData['outboundLog'] = $this->LogService->getLogOutboundSummary($this->account_id);
        $layoutData['accountInfo'] = $this->Service->getAccountInfo($this->account_id);        
        $layoutData['smsInfo'] = $this->LogService->getSmsInfo($this->account_id);
        
        // Return collection of list as a reosurce
        return response()->json($layoutData);   
    }

    /**
     * Check for auto login.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAuthCheck(Request $request, $account_id, $extn)
    {
        if(!$this->CheckUserSessionData($account_id, $extn)){
            echo 'User not Found!';
            die();
        }
        $pbxDbName = config('database.pbx_db_name');
        $user = User::selectRaw("user.*, up.email, IF(up.timezone='', ap.timezone, up.timezone) as timezone, up.password")
        ->leftJoin($pbxDbName.'.user_profile as up','user.userid','up.userid')
        ->leftJoin($pbxDbName.'.account_profile as ap','user.account_id','ap.account_id')
        //->where(['user.account_id' => $account_id,'user.extn' => $extn,'user.status' => 'A'])
        ->where(['user.account_id' => $account_id,'user.extn' => $extn,'user.active' => 'Y', 'user.allow_sms' => 'Y'])
        ->first();
        if($user){
            Auth::login($user);
            $request->session()->put('loginUser', $user->getOriginal());
            return redirect('/');
        }else{
            echo 'User authentication failed!';
            die();
        }
    }

    public function CheckUserSessionData($account_id, $extn) {
        $prefix="NPABX_";
        $admin_prefix="NPABXAD_";
        session_start();
        include(app_path() . '/session_class.php');
        // var_dump($_SESSION);
        // die();
		
		if (isset ( $_SESSION [$admin_prefix.'loggedAdminData'] )) {
				$data = unserialize ( $_SESSION [$admin_prefix.'loggedAdminData'] );
				if (isset($data->LoggedIn)&& $data->LoggedIn) {
				if(isset($data->account_id) && $data->account_id){
					if($data->account_id==$account_id){
						return true;
					}
				}
			}
		}	
		if (isset ( $_SESSION [$prefix.'loggedCallCenterData'] )) {
			$data = unserialize ( $_SESSION [$prefix.'loggedCallCenterData'] );
			if (isset($data->LoggedIn)&& $data->LoggedIn) {
				if(isset($data->account_id) && $data->account_id){
					if($data->account_id==$account_id){
						return true;
					}
				}
			}
		} 	
		if (isset ( $_SESSION [$prefix.'loggedUserData'] )) {
			$data = unserialize ( $_SESSION [$prefix.'loggedUserData'] );
			if (isset($data->LoggedIn)&& $data->LoggedIn) {
				if(isset($data->account_id) && $data->account_id){
					if($data->account_id==$account_id){
						return true;
					}
				}
			}
		} 
		
		if (isset ( $_SESSION [$prefix.'loggedExtnUserData'] )) {
			$data = unserialize ( $_SESSION [$prefix.'loggedExtnUserData'] );		
			if (isset($data->LoggedIn)&& $data->LoggedIn) {
				if(isset($data->account_id) && $data->account_id){
					if($data->account_id==$account_id){
						return true;
					}
				}
			}
		} 
			
		return null;
	}
}
