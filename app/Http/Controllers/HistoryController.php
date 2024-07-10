<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SchedulesService;
use App\Services\SchedulesLogService;

class HistoryController extends Controller
{
    public $Service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->Service = new SchedulesLogService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $authUser = \Session::get('loginUser');

        $data = $this->Service->getPagination($request); 
        $layoutData['js_plugin'] = $this->getJsPlugin(["JSP_BOOTSTRAP_BOOTBOX","JSP_SORTABLE"]);
        $layoutData['userType'] = config("dashboard_constant.USER_TYPE");
        $layoutData['userStatus'] = config("dashboard_constant.USER_STATUS");
        $layoutData['status'] = config("dashboard_constant.LOG_SMS_STATUS");
        $layoutData['userTimeZone'] = $authUser['timezone'];
        $layoutData['title'] = 'History List | '.config("app.name");
        $layoutData['breadcrumb'] = [
            "links" => [
                [
                    "name" => "History",
                    "url" => url("#/history-list"),
                    "icon" => "flaticon-user"
                ],
                [
                    "name" => "History List",
                    "url" => url("#/history-list"),
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
                    "url" => url("#/history-list"),                        
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getScheduleDetail($id)
    {
       
    }
}
