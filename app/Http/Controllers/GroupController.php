<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Services\GroupService;

class GroupController extends Controller
{

    public $Service;
   
   /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->Service = new GroupService();
    }

    public function getList()
    {
        // Get users
        $data = $this->Service->getPagination(); 
        $layoutData['js_plugin'] = $this->getJsPlugin(["JSP_BOOTSTRAP_BOOTBOX","JSP_SORTABLE"]);
        $layoutData['userType'] = config("dashboard_constant.USER_TYPE");
        $layoutData['userStatus'] = config("dashboard_constant.USER_STATUS");
        $layoutData['title'] = 'Group List | '.config("app.name");
        $layoutData['breadcrumb'] = [
            "links" => [
                [
                    "name" => "Group",
                    "url" => url("#/group-list"),
                    "icon" => "flaticon-list-1"
                ]
            ],
            "singleButton" =>[
                [
                    "name" => "Add Group",
                    "url" => url("#/group-create"),
                    "icon" => "la la-plus",
                    "class" => "m-btn--air btn-accent"
                ]
            ],
            "reloadButton" =>[
                [
                    "name" => "Reload",
                    "url" => url("#/group-list"),                        
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

    public function getSearchList(Request $request)
    {
        // Get userssearch list
        $data = $this->Service->getSearchList($request->input('q'));
        $layoutData['data'] = $data; 
        
        // Return collection of list as a reosurce
		return response()->json($layoutData);   
    }

    public function postGroupList(Request $request)
    {
        $data = $this->Service->getGroupList($request->input('ids'));
        $layoutData['data'] = $data;        
		return response()->json($layoutData);   
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $layoutData['userType'] = config("dashboard_constant.USER_TYPE");
        $layoutData['userStatus'] = config("dashboard_constant.USER_STATUS");
        $layoutData['title'] = 'Add Group | '.config("app.name");
        $layoutData['sms_text_size'] = config("dashboard_constant.SMS_TEXT_SIZE");
        $layoutData['sms_text_part'] = config("dashboard_constant.SMS_TEXT_PART");
        $layoutData['sms_text_part_size'] = config("dashboard_constant.SMS_TEXT_PART_SIZE");
        $layoutData['breadcrumb'] = [
            "links" => [
                [
                    "name" => "Group",
                    "url" => url("#/group-list"),
                    "icon" => "flaticon-user"
                ],
                [
                    "name" => "Add Group",
                    "url" => url("#/group-create"),
                    "icon" => "flaticon-plus"
                ]
            ]    
        ];
        return $layoutData;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {                
        $data = $this->Service->save($request);             
        $responseMsg = [
            config('msg_label.RESPONSE_MSG') => [
                config('msg_label.MSG_TYPE') => $data[config('msg_label.MSG_RESULT')] == true ? config('msg_label.MSG_SUCCESS') : config('msg_label.MSG_ERROR'),
                config('msg_label.MSG_TITLE') => $data[config('msg_label.MSG_RESULT')] == true ? config('msg_label.MSG_SUCCESS_TITLE') : config('msg_label.MSG_ERROR_TITLE'), 
                config('msg_label.MSG_MESSAGE') => $data[config('msg_label.MSG_MESSAGE')]
            ]
        ];
        return response()->json($responseMsg); 
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
        //Get group
        $data = $this->Service->getDetail($id);
        $layoutData['js_plugin'] = $this->getJsPlugin(["JSP_BOOTSTRAP_BOOTBOX"]);
        $layoutData['userType'] = config("dashboard_constant.USER_TYPE");
        $layoutData['userStatus'] = config("dashboard_constant.USER_STATUS");
        $layoutData['title'] = 'Group List | '.config("app.name");
        $layoutData['breadcrumb'] = [
            "links" => [
                [
                    "name" => "Group",
                    "url" => url("#/group-list"),
                    "icon" => "flaticon-list-1"
                ]
            ]
        ];
        $layoutData['data'] = $data;
        return response()->json($layoutData); 
    }

    
    /**
     * Add contact to group.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postAddContactToGroup(Request $request, $id)
    {
        $data = $this->Service->addContactToGroup($request, $id); 
        $responseMsg = $this->Service->processControllerResponse($data[config('msg_label.MSG_RESULT')], $data[config('msg_label.MSG_MESSAGE')]);
        return response()->json($responseMsg); 
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
        $data = $this->Service->updateData($request); 
        $responseMsg = $this->Service->processControllerResponse($data[config('msg_label.MSG_RESULT')], $data[config('msg_label.MSG_MESSAGE')]);
        return response()->json($responseMsg); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->Service->delete($id);
        $responseMsg = $this->Service->processControllerResponse($data[config('msg_label.MSG_RESULT')], $data[config('msg_label.MSG_MESSAGE')]);
        return response()->json($responseMsg);
    }

    /**
     * Get Group Detail.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getGroupDetail($id)
    {
        $data = $this->Service->getGroupDetail($id);
        return response()->json($data);
    }
}
