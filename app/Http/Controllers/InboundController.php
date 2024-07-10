<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Services\InboundService;
use App\Models\Inbound;

class InboundController extends Controller
{

    public $Service;
   
   /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->Service = new InboundService();
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
                        "name" => "Inbound List",
                        "url" => url("#/inbound-list"),
                        "icon" => "flaticon-list-1"
                    ]
                ],
				"download" =>[
                [
                    "name" => "Download CSV",
                    "url" => url("#"),                        
                    "icon" => "la la-download",
                    "class" => "m-btn--air btn-accent"
                ]
            ],
                "reloadButton" =>[
                    [
                        "name" => "Reload",
                        "url" => url("#/inbound-list"),                        
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
