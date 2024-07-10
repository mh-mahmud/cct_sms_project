<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\User;
use App\Models\Group;
use App\Models\ContactGroup;
use Illuminate\Support\Facades\Hash;
use DB;
use Validator;
use Auth;
use App\Services\ContactsService;
use App\Services\SchedulesService;
use App\Services\AuditLogService;

class GroupService extends AppService
{

    public function __construct()
    {
        $this->ContactsService = new ContactsService();
        $this->SchedulesService = new SchedulesService();
        $this->AuditLogService = new AuditLogService();
        $this->extn = Auth::user()->extn;
        $this->did = Auth::user()->did;
        $this->user_id = Auth::user()->userid;
    }
    
    /**
     * get pagination data
     */
    public function getPagination(){
        // Get list

        $per_page = (isset($queryParam['per_page']) && !empty($queryParam['per_page']) && $queryParam['per_page']!='undefined') ? $queryParam['per_page'] : config('dashboard_constant.PAGINATION_LIMIT');

        $data = Group::where('account_id','=', $this->getAccountId())->where('user_id','=', $this->user_id)->orderBy('created_at', 'DESC')->paginate($per_page); 
        return $this->paginationDataFormat($data->toArray());
    }

    /**
     * get all group data
     */
    public function getAllGroups(){
        // Get list
        $lists = array();   
        $data = Group::where('account_id', $this->getAccountId())                
                ->where('user_id', $this->user_id)
                ->get()                
                ->toArray();
        $lists = $data;
        return $lists;
    }

    /**
     * get search data
     */
    public function getSearchList($q){
        // Get list
        $lists = array();   
        if($q!="" || $q!=null){
            $data = Group::where('name', 'LIKE', '%'.$q.'%')                
                ->get()                
                ->toArray();
            $lists = $data;
        }
        return $lists;
    }

    

    /**
     * get list data
     */
    public function getGroupList($ids){
        // Get list
        $data = [];   
        if($ids!="" || $ids!=null){ 
            $data = Group::select(DB::raw('id, CONCAT(name, "(", num_contacts, ")") AS name'))
                ->whereIn('id', $ids)                    
                ->get()
                ->toArray();
        }
        return $data;
    }

    /**
     * add contact to group
     * @param array request
     */
    public function addContactToGroup($request, $group_id){

        $contacts = $request->input('contacts');

        if(!empty($contacts)){
            foreach ($contacts as $key => $value) {
                $obj = new ContactGroup();
                $obj->account_id = $this->getAccountId();
                //$obj->extn = $this->extn;
                $obj->group_id = $group_id;
                $obj->contact_id = $value;
                $obj->save();
            }
            $this->updateGroupContactNumber($group_id, count($contacts));
            return $this->processServiceResponse(true, "Contact Added Successfully!", $contacts);
        }
        return $this->processServiceResponse(false, "Contact Added Failed!", $contacts);
    }

    /**
     * update group contact number
     * @param int $id
     */
    public function updateGroupContactNumber($group_id, $num_contacts){
        $updateList = Group::find($group_id);
            if($updateList){
                $updateList->num_contacts = $updateList->num_contacts + $num_contacts;
                $updateList->save();
            }
    }

    
    /**
     * save data
     * @param array request
     */
    public function save($request){

        $rules = [
            'name' => 'required',
        ];

        Validator::make($request->all(),$rules)->validate();

        // Create or Update        
        
        $list = new Group;
        $listid = $this->genListId();
        $list->id = $listid;
        $list->name = $request->input('name');
        $list->account_id = $this->getAccountId();
//        $list->extn = $this->extn;
        $list->user_id = \Auth::user()->userid;
        $list->num_contacts = 0;
        $list->created_by = \Auth::user()->userid;
        $list->updated_by = \Auth::user()->userid;

        if($list->save()) {
            $list->id = $listid;
            $this->AuditLogService->createLog($list, 'A');
            return $this->processServiceResponse(true, "Group Added Successfully!", $list);           
        }

        return $this->processServiceResponse(false, "Group Added Failed!", $list);
    }

    /**
     * GENERATE RANDOM GROUP ID
     */
    public function genListId(){
        $id = $this->genRandNum(10);
        $IdExists = Group::find($id); 
        if($IdExists){
            return $this->genListId();
        }
        return $id;
    }

    /**
     * GET ACCOUNT ID FORM SESSION
     */
    public function getAccountId(){
        $value = \Auth::user()->account_id;
        return $value;
    }

    /**
     * get details
     * $param int $id
     */
    public function getDetail($id){
        //Get detail
        return Group::findOrFail($id);
    }

    /**
     * get group details with contacts
     * $param int $id
     */
    public function getGroupDetail($id){
        //Get detail
        return Group::with('contacts')->find($id)->toArray();
    }

    /**
     * update data
     * @param array request
     */
    public function updateData($request){
        $rules = [
            'name' => 'required'
        ];
        
        Validator::make($request->all(),$rules)->validate();
        
        // get detail
        $dataObj = $this->getDetail($request->id);        
        $dataObj->name = $request->input('name');     
        $dataObj->updated_by = \Auth::user()->userid;
        $dataObjU = $dataObj->getDirty();
        if($dataObj->save()) {
            $this->AuditLogService->createLog($dataObjU, 'U');
            return $this->processServiceResponse(true, "Group Update Successfully!", $dataObj);
        }

        return $this->processServiceResponse(false, "Group Update Failed!", $dataObj);
    }

    /**
     * delete data
     * @param int $id
     */
    public function delete($id){
        $dataObj = $this->getDetail($id);
       
        if($dataObj->delete()) {
            $this->AuditLogService->createLog($dataObj, 'D');
            if($this->ContactsService->deleteContactsByGroupId($id)){
                //return $this->processServiceResponse(true, "Group Deleted Successfully!", $dataObj);
            }          
        }

        return $this->processServiceResponse(true, "Group Deleted Successfully!", $dataObj);
        //return $this->processServiceResponse(false, "Group Deleted Failed!", $dataObj);        
    }
    /**
     * search group by group name
     * @param char $queryParam
     */
    public function getSearchGroupData($queryParam){         
        $user_id = \Auth::user()->userid;
        $query = Group::where('user_id', '=', $user_id);
        if(isset($queryParam['q']) && !empty($queryParam['q'])){
            $query->where('name', "LIKE","%".$queryParam['q']."%");
        }
        $list = $query->get()->toArray();
        return $list;
    }


}
