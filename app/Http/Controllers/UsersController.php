<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Services\UsersService;
use App\Services\AccountService;


class UsersController extends AppController
{
    public $Service;
   
   /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->Service = new UsersService();
        $this->AccountService = new AccountService();
    }
    

    public function getUsersList()
    {
        // Get users
        $data = $this->Service->getPagination(); 
        $layoutData['js_plugin'] = $this->getJsPlugin(["JSP_BOOTSTRAP_BOOTBOX"]);
        $layoutData['userType'] = config("dashboard_constant.USER_TYPE");
        $layoutData['userStatus'] = config("dashboard_constant.USER_STATUS");
        $layoutData['title'] = 'User List | '.config("app.name");
        $layoutData['breadcrumb'] = [
            "links" => [
                [
                    "name" => "User",
                    "url" => url("#/user-list"),
                    "icon" => "flaticon-user"
                ],
                [
                    "name" => "User List",
                    "url" => url("#/user-list"),
                    "icon" => "flaticon-list-1"
                ]
            ],
            "singleButton" =>[
                [
                    "name" => "Add User",
                    "url" => url("#/user-create"),
                    "icon" => "la la-plus",
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

    public function create(){
        $layoutData['userType'] = config("dashboard_constant.USER_TYPE");
        $layoutData['userStatus'] = config("dashboard_constant.USER_STATUS");
        $layoutData['title'] = 'User Create | '.config("app.name");
        $layoutData['breadcrumb'] = [
            "links" => [
                [
                    "name" => "User",
                    "url" => url("#/user-list"),
                    "icon" => "flaticon-user"
                ],
                [
                    "name" => "User Create",
                    "url" => url("#/user-create"),
                    "icon" => "flaticon-plus"
                ]
            ]    
        ];
        return $layoutData;
    }

    public function getPasswordReset(){
        $layoutData['userType'] = config("dashboard_constant.USER_TYPE");
        $layoutData['userStatus'] = config("dashboard_constant.USER_STATUS");
        $layoutData['title'] = 'Reset password | '.config("app.name");
        $layoutData['breadcrumb'] = [
            "links" => [
                [
                    "name" => "Reset password",
                    "url" => url("#/user-reset-password"),
                    "icon" => "flaticon-user"
                ]
            ]    
        ];
        return $layoutData;
    }

    public function postPasswordReset(Request $request){     
           
        $data = $this->Service->passwordReset($request); 
        $responseMsg = $this->Service->processControllerResponse($data[config('msg_label.MSG_RESULT')], $data[config('msg_label.MSG_MESSAGE')]);
        return response()->json($responseMsg); ;
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
        //Get user
        $data = $this->Service->getDetail($id); 
        $layoutData['userType'] = config("dashboard_constant.USER_TYPE");
        $layoutData['userStatus'] = config("dashboard_constant.USER_STATUS");
        $layoutData['title'] = 'User Detail | '.config("app.name");
        $layoutData['breadcrumb'] = [
            "links" => [
                [
                    "name" => "User",
                    "url" => url("#/user-list"),
                    "icon" => "flaticon-user"
                ],
                [
                    "name" => "User Detail",
                    "url" => url("#/user-detail/".$id),
                    "icon" => "flaticon-interface-9"
                ]
            ],
            "singleButton" =>[
                [
                    "name" => "Edit User",
                    "url" => url("#/user-edit/".$id),
                    "icon" => "la la-pencil",
                    "class" => "m-btn--air btn-accent"
                ]

            ]       
        ];
        $layoutData['data'] = $data;
        // Return single user as resurce
        return response()->json($layoutData); 
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id){
        //Get user
        $data = $this->Service->getDetail($id);
        $layoutData['userType'] = config("dashboard_constant.USER_TYPE");
        $layoutData['userStatus'] = config("dashboard_constant.USER_STATUS");
        $layoutData['title'] = 'User Edit | '.config("app.name");
        $layoutData['breadcrumb'] = [
            "links" => [
                [
                    "name" => "User",
                    "url" => url("#/user-list"),
                    "icon" => "flaticon-user"
                ],
                [
                    "name" => "User Edit",
                    "url" => url("#/user-edit/".$id),
                    "icon" => "flaticon-edit-1"
                ]
            ]
        ];
        $layoutData['data'] = $data;
        return response()->json($layoutData); 
    }

    /**
     * Update a  resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
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
