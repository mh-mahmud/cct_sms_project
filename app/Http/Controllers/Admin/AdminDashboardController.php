<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Services\Admin\UsersService;
use App\Services\Admin\LogService;
use Auth;
use Session;

class AdminDashboardController extends Controller
{
    public $Service;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        if (!Auth::guard('admin')->check()) {
            return abort(401);
        }
        $this->middleware('auth:admin');
        $this->account_id = Auth::guard('admin')->user() ? Auth::guard('admin')->user()->account_id : '';
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
        
        return view('admin.dashboard.index', [
            'title' => 'Admin Dashboard',
            'search' => '',
            'sub_header' => 'Admin Dashboard',
            'sidebar_menu_active' => 'dashboard',
            'sidebar_submenu_active' => '',
            'sidebar_subsubmenu_active' => '',
        ]);
    }
    public function getDashboardData()
    {        
        $layoutData['js_plugin'] = $this->getJsPlugin(["JSP_BOOTSTRAP_BOOTBOX"]);
        $layoutData['userType'] = config("dashboard_constant.USER_TYPE");
        $layoutData['userStatus'] = config("dashboard_constant.USER_STATUS");
        $layoutData['logSmsStatus'] = config("dashboard_constant.LOG_SMS_STATUS");
        $layoutData['smsDirection'] = config("dashboard_constant.SMS_DIRECTION");
        $layoutData['title'] = 'Dashboard | '.config("app.name");
        $layoutData['sidebar_menu_active'] = 'dashboard';
        $layoutData['timezone'] =  Auth::guard('admin')->user()->timezone;
        $layoutData['breadcrumb'] = [
            "links" => [
                [
                    "name" => "Dashboard",
                    "url" => url("#/admin/dashboard"),
                    "icon" => "flaticon-dashboard"
                ]
            ]  
        ];
        $layoutData['unreadLog'] = $this->LogService->getLogUnreadSummary($this->account_id);
        $layoutData['inboundLog'] = $this->LogService->getLogInboundSummary($this->account_id);
        $layoutData['outboundLog'] = $this->LogService->getLogOutboundSummary($this->account_id);
        $layoutData['accountInfo'] = $this->Service->getAccountInfo($this->account_id);
        
        // Return collection of list as a reosurce
        return response()->json($layoutData);   
    }
}