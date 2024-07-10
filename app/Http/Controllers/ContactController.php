<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Services\ContactsService;
use App\Services\GroupService;

class ContactController extends Controller
{

    public $Service;
   
   /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->Service = new ContactsService();
        $this->GroupService = new GroupService();
    }
    /**
     * get contact list
     */
    public function getContactList(Request $request)
    {
        // Get users
        $data = $this->Service->getPagination($request); 
        $layoutData['js_plugin'] = $this->getJsPlugin(["JSP_BOOTSTRAP_BOOTBOX","JSP_SORTABLE"]);
        $layoutData['userType'] = config("dashboard_constant.USER_TYPE");
        $layoutData['userStatus'] = config("dashboard_constant.USER_STATUS");
        $layoutData['phoneType'] = ['M' => 'Mobile', 'L' => 'Landline'];
        $layoutData['title'] = 'Contact List | '.config("app.name");
       $layoutData['breadcrumb'] = [
            "links" => [
                [
                    "name" => "Contact",
                    "url" => url("#/contact-list"),
                    "icon" => "flaticon-list-1"
                ]
            ],
            "reloadButton" =>[
                [
                    "name" => "Reload",
                    "url" => url("#/contact-list"),                        
                    "icon" => "la la-refresh",
                    "class" => "m-btn--air btn-accent"
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
                    "name" => "Add Contact",
                    "url" => url("#/contact-create"),
                    "icon" => "la la-plus",
                    "class" => "m-btn--air btn-accent"
                ],
                [
                    "name" => "Import Contact",
                    "url" => url("#/contact-import"),
                    "icon" => "la la-book",
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
     * get contact list modal filter by group select all
     */
    public function getContactListModalFilterSelectAll(Request $request, $id)
    {
        // Get                
        $data = $this->Service->addContactsToGroupSelectAll($request, $id);         
        $responseMsg = $this->Service->processControllerResponse($data[config('msg_label.MSG_RESULT')], $data[config('msg_label.MSG_MESSAGE')]);
        return response()->json($responseMsg);   
    }
    /**
     * get contact list modal filter by group
     */
    public function getContactListModalFilter(Request $request, $id)
    {
        // Get
        $data = $this->Service->getPaginationModalFilter($request, $id);         
        $layoutData['userType'] = config("dashboard_constant.USER_TYPE");
        $layoutData['userStatus'] = config("dashboard_constant.USER_STATUS");                

        $layoutData['data'] = $data['data']; 
        // pagination meta value
        $layoutData['meta'] = $data['meta'];
        // pagination links
        $layoutData['links'] = $data['links'];
        
        // Return collection of list as a reosurce
		return response()->json($layoutData);   
    }

    /**
     * get contact list modal
     */
    public function getContactListModal(Request $request)
    {
        // Get users
        $data = $this->Service->getPaginationModal($request);         
        $layoutData['userType'] = config("dashboard_constant.USER_TYPE");
        $layoutData['userStatus'] = config("dashboard_constant.USER_STATUS");                

        $layoutData['data'] = $data['data']; 
        // pagination meta value
        $layoutData['meta'] = $data['meta'];
        // pagination links
        $layoutData['links'] = $data['links'];
        
        // Return collection of list as a reosurce
		return response()->json($layoutData);   
    }
    /**
     * get contacts by Group Id
     */
    public function getContactListByGroup(Request $request, $group_id)
    {
        // Get contacts
        $data = $this->Service->getContactListByGroup($group_id);
        $layoutData['js_plugin'] = $this->getJsPlugin(["JSP_BOOTSTRAP_BOOTBOX","JSP_SORTABLE"]);
        $layoutData['userType'] = config("dashboard_constant.USER_TYPE");
        $layoutData['userStatus'] = config("dashboard_constant.USER_STATUS");
        $layoutData['title'] = 'Group Contacts | '.config("app.name");
        $layoutData['group'] = $this->GroupService->getDetail($group_id);
        $layoutData['breadcrumb'] = [
            "links" => [
                [
                    "name" => "Groups",
                    "url" => url("#/group-list"),
                    "icon" => "flaticon-list-1"
                ],
                [
                    "name" => "Group Contacts",
                    "url" => url("#/contact-group-list/".$group_id),
                    "icon" => "flaticon-list-1"
                ]
            ],
            "modalButton" =>[
                [
                    "name" => "Add Contact",
                    "url" => 'javascript:void(0)',
                    "icon" => "la la-plus",
                    "class" => "m-btn--air btn-accent",
                    "toggle" => "modal",
                    "target" => "#contact-modal"
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

    public function getContactImport()
    {
        
        $layoutData['title'] = 'Contact Import | '.config("app.name");
        $layoutData['exampleFile'] = url(config("dashboard_constant.CSV_EXAMPLE_FILE_PATH"));
        $layoutData['groups'] = $this->GroupService->getAllGroups();
        $layoutData['breadcrumb'] = [
            "links" => [
                [
                    "name" => "Contacts",
                    "url" => url("#/contact-list"),
                    "icon" => "flaticon-list-1"
                ],
                [
                    "name" => "Import Contacts",
                    "url" => url("#/contact-import"),
                    "icon" => "flaticon-list-1"
                ]
            ]
        ];        
        return response()->json($layoutData);  
    }

    public function postContactImport(Request $request)
    {
        $data = $this->Service->import($request);
        $responseMsg = $this->Service->processControllerResponseWithData($data[config('msg_label.MSG_RESULT')], $data[config('msg_label.MSG_MESSAGE')], $data[config('msg_label.MSG_DATA')]);
        
        return response()->json($responseMsg);  
    }

    public function postContactImportCreate(Request $request)
    {
        $data = $this->Service->importCreate($request);
        $responseMsg = $this->Service->processControllerResponseWithData($data[config('msg_label.MSG_RESULT')], $data[config('msg_label.MSG_MESSAGE')], $data[config('msg_label.MSG_DATA')]);
        
        return response()->json($responseMsg);    
    }

    public function getCountries(){
        $data = config('country');
        return response()->json($data); 
    }

    public function postContactList(Request $request)
    {
        $data = $this->Service->getContactList($request->input('ids'));
        $layoutData['data'] = $data;        
		return response()->json($layoutData);   
    }

    public function getCountryPhoneCode($code){        
        $data = config('code');        
        if(!empty($data[$code])){
            return response()->json($data[$code]);
        }
        return response()->json(""); 
    }    
    
    public function getCountryList(){
        $data = config('country');
        return $data;
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
        $layoutData['title'] = 'Add Contact | '.config("app.name");
        $layoutData['groups'] = $this->GroupService->getAllGroups();
        $layoutData['breadcrumb'] = [
            "links" => [
                [
                    "name" => "Contact",
                    "url" => url("#/contact-list"),
                    "icon" => "flaticon-user"
                ],
                [
                    "name" => "Add Contact",
                    "url" => url("#/contact-create"),
                    "icon" => "flaticon-plus"
                ]
            ]    
        ];
        $layoutData['countries'] = $this->getCountryList();
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
        //Get contact
        $data = $this->Service->getContactDetail($id);
        $layoutData['userType'] = config("dashboard_constant.USER_TYPE");
        $layoutData['userStatus'] = config("dashboard_constant.USER_STATUS");
        $layoutData['title'] = 'Edit Contact | '.config("app.name");
        $layoutData['groupList'] = $this->GroupService->getAllGroups();
        $layoutData['breadcrumb'] = [
            "links" => [
                [
                    "name" => "Contact",
                    "url" => url("#/contact-list"),
                    "icon" => "flaticon-user"
                ],
                [
                    "name" => "Contact Create",
                    "url" => url("#/contact-create"),
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
     * Remove the specified contact form group by selected all.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  char  $group_id
     * @return \Illuminate\Http\Response
     */
    public function postDeleteContactFromGroupSelectedAll(Request $request, $group_id)
    {
        $data = $this->Service->deleteContactFromGroupSelectedAll($request,$group_id);
        $responseMsg = $this->Service->processControllerResponse($data[config('msg_label.MSG_RESULT')], $data[config('msg_label.MSG_MESSAGE')]);        
        return response()->json($responseMsg);  
    }

    /**
     * Remove the specified contact form group by selected IDs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  char  $group_id
     * @return \Illuminate\Http\Response
     */
    public function postDeleteContactFromGroupSelected(Request $request, $group_id)
    {
        $data = $this->Service->deleteContactFromGroupSelected($request,$group_id);
        $responseMsg = $this->Service->processControllerResponse($data[config('msg_label.MSG_RESULT')], $data[config('msg_label.MSG_MESSAGE')]);        
        return response()->json($responseMsg);  
    }

    /**
     * Remove the specified contact form group.
     *
     * @param  char  $id
     * @param  char  $group_id
     * @return \Illuminate\Http\Response
     */
    public function postDeleteContactFromGroup($id,$group_id)
    {
        $data = $this->Service->deleteContact($id,$group_id);
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
     * get contact options/dropdown list
     * @return \Illuminate\Http\Response
     */
    public function getSearchContactList(Request $request){
        $data = $this->Service->getSearchData($request);
        return response()->json($data); 
    }
}
