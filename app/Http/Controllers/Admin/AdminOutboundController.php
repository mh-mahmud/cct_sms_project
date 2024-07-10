<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Services\Admin\OutboundService;
use Auth;

class AdminOutboundController extends Controller
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
        $this->Service = new OutboundService();
    }

    public function getList(Request $request)
    {
        
        $authUser = \Session::get('loginUser'); 

        $data = $this->Service->getPagination($request); 
        $layoutData['js_plugin'] = $this->getJsPlugin(["JSP_BOOTSTRAP_BOOTBOX","JSP_SORTABLE"]);
        $layoutData['userType'] = config("dashboard_constant.USER_TYPE");
        $layoutData['userStatus'] = config("dashboard_constant.USER_STATUS");
        $layoutData['directions'] = config("dashboard_constant.SMS_DIRECTION");
        $layoutData['userTimeZone'] = $authUser['timezone'];
        $layoutData['title'] = 'Text List | '.config("app.name");
        $layoutData['breadcrumb'] = [
            "links" => [
                [
                    "name" => "Outbound List",
                    "url" => url("#/admin/outbound-list"),
                    "icon" => "flaticon-list-1"
                ]
            ],
            "reloadButton" =>[
                [
                    "name" => "Reload",
                    "url" => url("#/admin/outbound-list"),                        
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
        // mas date difference
        $layoutData['maxDateDiff'] = config("dashboard_constant.REPORT_MAX_DATE_DIFF");
        
        // Return collection of list as a reosurce
		return response()->json($layoutData);   
    }

    public function getInboxList(Request $request, $from, $to){

        $data = $this->Service->getInboxList($request, $from, $to);
        return response()->json($data); 
    }
}
