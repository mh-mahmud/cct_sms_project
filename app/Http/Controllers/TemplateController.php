<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TemplatesService;

class TemplateController extends Controller
{
    public $Service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->Service = new TemplatesService();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get users
        $data = $this->Service->getPagination(); 
        $layoutData['js_plugin'] = $this->getJsPlugin(["JSP_BOOTSTRAP_BOOTBOX","JSP_SORTABLE"]);
        $layoutData['userType'] = config("dashboard_constant.USER_TYPE");
        $layoutData['userStatus'] = config("dashboard_constant.USER_STATUS");
        $layoutData['title'] = 'Template List | '.config("app.name");
        $layoutData['breadcrumb'] = [
            "links" => [
                [
                    "name" => "Template",
                    "url" => url("#/template-list"),
                    "icon" => "flaticon-user"
                ],
                [
                    "name" => "Template List",
                    "url" => url("#/template-list"),
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
            "singleButton" =>[
                [
                    "name" => "Add Template",
                    "url" => url("#/template-create"),
                    "icon" => "la la-plus",
                    "class" => "m-btn--air btn-accent"
                ]
            ],
            "reloadButton" =>[
                [
                    "name" => "Reload",
                    "url" => url("#/template-list"),                        
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
        $layoutData['userType'] = config("dashboard_constant.USER_TYPE");
        $layoutData['userStatus'] = config("dashboard_constant.USER_STATUS");
        $layoutData['title'] = 'Template Create | '.config("app.name");
        $layoutData['sms_text_size'] = config("dashboard_constant.SMS_TEXT_SIZE");
        $layoutData['sms_text_part'] = config("dashboard_constant.SMS_TEXT_PART");
        $layoutData['sms_text_part_size'] = config("dashboard_constant.SMS_TEXT_PART_SIZE");
        $layoutData['breadcrumb'] = [
            "links" => [
                [
                    "name" => "Template",
                    "url" => url("#/template-list"),
                    "icon" => "flaticon-user"
                ],
                [
                    "name" => "Add Template",
                    "url" => url("#/template-create"),
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
        $responseMsg = $this->Service->processControllerResponse($data[config('msg_label.MSG_RESULT')], $data[config('msg_label.MSG_MESSAGE')]);
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
        //Get template
        $data = $this->Service->getDetail($id);
        $layoutData['userType'] = config("dashboard_constant.USER_TYPE");
        $layoutData['userStatus'] = config("dashboard_constant.USER_STATUS");
        $layoutData['title'] = 'User Edit | '.config("app.name");
        $layoutData['sms_text_size'] = config("dashboard_constant.SMS_TEXT_SIZE");
        $layoutData['sms_text_part'] = config("dashboard_constant.SMS_TEXT_PART");
        $layoutData['sms_text_part_size'] = config("dashboard_constant.SMS_TEXT_PART_SIZE");
        $layoutData['breadcrumb'] = [
            "links" => [
                [
                    "name" => "Template",
                    "url" => url("#/template-list"),
                    "icon" => "flaticon-user"
                ],
                [
                    "name" => "Edit Template",
                    "url" => url("#/template-create"),
                    "icon" => "flaticon-plus"
                ]
            ]    
        ];
        $layoutData['data'] = $data;
        return response()->json($layoutData); 
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
}
