<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\Admin\AuditLogService;
use Session;
use Auth;

class AdminAuditLogController extends Controller
{
    public $Service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (!Auth::guard('admin')->check()) {
            return abort(401);
        }
        $this->middleware('auth:admin');
        $this->Service = new AuditLogService();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get logs

        $authUser = Session::get('loginUser');

        $data = $this->Service->getPagination($request); 
        $layoutData['js_plugin'] = $this->getJsPlugin(["JSP_BOOTSTRAP_BOOTBOX","JSP_SORTABLE"]);
        $layoutData['userType'] = config("dashboard_constant.USER_TYPE");
        $layoutData['userStatus'] = config("dashboard_constant.USER_STATUS");
        $layoutData['changeType'] = config("dashboard_constant.AUDIT_LOG_CHNAGE_TYPE");
        $layoutData['userTimeZone'] = $authUser['timezone'];
        $layoutData['title'] = 'Log List | '.config("app.name");
        $layoutData['breadcrumb'] = [
            "links" => [
                [
                    "name" => "Audit Log",
                    "url" => url("#/audit-log-list"),
                    "icon" => "flaticon-file"
                ]
            ],
            "reloadButton" =>[
                [
                    "name" => "Reload",
                    "url" => url("#/admin/audit-log-list"),                        
                    "icon" => "la la-refresh",
                    "class" => "m-btn--air btn-accent"
                ]
            ]
        ];
        $layoutData['data'] = $data['data']; 
        // pagination meta value
        $layoutData['meta'] = $data['meta'];
        // pagination links
        $layoutData['links'] = $data['links'];
        
        // Return collection of list as a reosurce
		return response()->json($layoutData);  
    }
}
