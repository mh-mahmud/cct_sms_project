<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\User;
use App\Models\Contact;
use App\Models\Group;
use App\Models\ContactGroup;
use Illuminate\Support\Facades\Hash;
use Validator;
use Auth;
use App\Services\DidService;
use App\Services\AuditLogService;

class ContactsService extends AppService
{

    public function __construct()
    {        
        $this->account_id = Auth::user()->account_id;
        $this->extn = Auth::user()->extn;
        $this->did = Auth::user()->did;
        $this->user_id = Auth::user()->userid;
        $this->DidService = new DidService();
        $this->AuditLogService = new AuditLogService();        
    }
    
    /**
     * get pagination data
     */
    public function getPagination($request){
        // Get list

        $queryParam = $request->query();

        $query = Contact::where('account_id','=', $this->account_id)->where('user_id','=', $this->user_id);

        if(isset($queryParam['phone']) && !empty($queryParam['phone']) ){
            $queryParam['phone'] = preg_replace("/[^0-9]/", "", $queryParam['phone']);
            $queryParam['phone'] = (strlen($queryParam['phone'])==10) ? '1'.$queryParam['phone'] : $queryParam['phone'];       
            $query->where("phone", 'LIKE', '%'.$queryParam['phone'].'%');            
        }
        if(isset($queryParam['first_name']) && !empty($queryParam['first_name']) ){            
            $query->where("first_name", 'LIKE', '%'.$queryParam['first_name'].'%');            
        }
        if(isset($queryParam['last_name']) && !empty($queryParam['last_name']) ){                       
            $query->where("last_name", 'LIKE', '%'.$queryParam['last_name'].'%');            
        }
        if(isset($queryParam['company']) && !empty($queryParam['company']) ){                       
            $query->where("company", 'LIKE', '%'.$queryParam['company'].'%');            
        }

        $per_page = (isset($queryParam['per_page']) && !empty($queryParam['per_page']) && $queryParam['per_page']!='undefined') ? $queryParam['per_page'] : config('dashboard_constant.PAGINATION_LIMIT');


        $query = $query->orderBy('created_at', 'DESC')->paginate($per_page); 
        return $this->paginationDataFormat($query->toArray());
    }

    /**
     * get pagination modal data filter by group select all
     */
    public function addContactsToGroupSelectAll($request, $group_id){
        // Get list

        $queryParam = $request->query();

        $query = Contact::where('account_id','=', $this->account_id)->where('user_id','=',$this->ususer_id);         

        if(isset($queryParam['phone']) && !empty($queryParam['phone']) ){
            $queryParam['phone'] = preg_replace("/[^0-9]/", "", $queryParam['phone']);
            $queryParam['phone'] = (strlen($queryParam['phone'])==10) ? '1'.$queryParam['phone'] : $queryParam['phone'];       
            $query->where("phone", 'LIKE', '%'.$queryParam['phone'].'%');            
        }
        if(isset($queryParam['first_name']) && !empty($queryParam['first_name']) ){            
            $query->where("first_name", 'LIKE', '%'.$queryParam['first_name'].'%');            
        }
        if(isset($queryParam['last_name']) && !empty($queryParam['last_name']) ){                       
            $query->where("last_name", 'LIKE', '%'.$queryParam['last_name'].'%');            
        }
        if(isset($queryParam['company']) && !empty($queryParam['company']) ){                       
            $query->where("company", 'LIKE', '%'.$queryParam['company'].'%');            
        }
        $query->whereRaw('contacts.id NOT IN (SELECT contact_id FROM contact_group WHERE account_id="'.$this->account_id.'" AND group_id="'.$group_id.'")');

        $contacts = $query->orderBy('created_at', 'DESC')->pluck('id')->toArray();
        if(!empty($contacts)){
            foreach ($contacts as $key => $value) {
                $obj = new ContactGroup();
                $obj->account_id = $this->getAccountId();
                $obj->group_id = $group_id;
                $obj->contact_id = $value;
                $obj->save();
            }
            $this->updateContactNumber($group_id, count($contacts));
            return $this->processServiceResponse(true, "Contact Added Successfully!", $contacts);
        }
        return $this->processServiceResponse(false, "Contact Added Failed!", $contacts);

    }

    /**
     * update group contact number
     * @param int $id
     */
    public function updateContactNumber($group_id, $num_contacts){
        $updateList = Group::find($group_id);
            if($updateList){
                $updateList->num_contacts = $updateList->num_contacts + $num_contacts;
                $updateList->save();
            }
    }



    /**
     * get pagination modal data filter by group
     */
    public function getPaginationModalFilter($request, $group_id){
        // Get list

        $queryParam = $request->query();

        $query = Contact::where('account_id','=', $this->account_id)->where('user_id','=',$this->user_id);         

        if(isset($queryParam['phone']) && !empty($queryParam['phone']) ){
            $queryParam['phone'] = preg_replace("/[^0-9]/", "", $queryParam['phone']);
            $queryParam['phone'] = (strlen($queryParam['phone'])==10) ? '1'.$queryParam['phone'] : $queryParam['phone'];       
            $query->where("phone", 'LIKE', '%'.$queryParam['phone'].'%');            
        }
        if(isset($queryParam['first_name']) && !empty($queryParam['first_name']) ){            
            $query->where("first_name", 'LIKE', '%'.$queryParam['first_name'].'%');            
        }
        if(isset($queryParam['last_name']) && !empty($queryParam['last_name']) ){                       
            $query->where("last_name", 'LIKE', '%'.$queryParam['last_name'].'%');            
        }
        if(isset($queryParam['company']) && !empty($queryParam['company']) ){                       
            $query->where("company", 'LIKE', '%'.$queryParam['company'].'%');            
        }
        $query->whereRaw('contacts.id NOT IN (SELECT contact_id FROM contact_group WHERE account_id="'.$this->account_id.'" AND group_id="'.$group_id.'")');

        $query = $query->orderBy('created_at', 'DESC')->paginate(20); 
        return $this->paginationDataFormat($query->toArray());
    }

    /**
     * get pagination modal data
     */
    public function getPaginationModal($request){
        // Get list

        $queryParam = $request->query();

        $query = Contact::where('account_id','=', $this->account_id)->where('user_id','=',$this->user_id);         

        if(isset($queryParam['phone']) && !empty($queryParam['phone']) ){
            $queryParam['phone'] = preg_replace("/[^0-9]/", "", $queryParam['phone']);
            $queryParam['phone'] = (strlen($queryParam['phone'])==10) ? '1'.$queryParam['phone'] : $queryParam['phone'];       
            $query->where("phone", 'LIKE', '%'.$queryParam['phone'].'%');            
        }
        if(isset($queryParam['first_name']) && !empty($queryParam['first_name']) ){            
            $query->where("first_name", 'LIKE', '%'.$queryParam['first_name'].'%');            
        }
        if(isset($queryParam['last_name']) && !empty($queryParam['last_name']) ){                       
            $query->where("last_name", 'LIKE', '%'.$queryParam['last_name'].'%');            
        }        

        $query = $query->orderBy('created_at', 'DESC')->paginate(20); 
        return $this->paginationDataFormat($query->toArray());
    }

    /**
     * get list data
     */
    public function getContactList($ids){
        // Get list
        $data = [];   
        if($ids!="" || $ids!=null){ 
            $data = Contact::select(\DB::raw('contacts.phone AS code, CONCAT(COALESCE(contacts.first_name,""), " ", COALESCE(contacts.last_name,""), " (",contacts.phone,")") AS value'))
                ->whereIn('id', $ids)                    
                ->get()
                ->toArray();
        }
        return $data;
    }

    /**
     * get contact list bt Group Id
     * @param char group_id
     */
    public function getContactListByGroup($group_id){
        // Get list

        $data = Contact::join('contact_group', 'contacts.id', '=', 'contact_group.contact_id')
                        ->where('contact_group.account_id','=', $this->account_id)                        
                        ->where('contact_group.group_id','=', $group_id)
                        ->select('contacts.*')
                        ->orderBy('created_at', 'DESC')
                        ->paginate(config('dashboard_constant.PAGINATION_LIMIT')); 
        return $this->paginationDataFormat($data->toArray());
    }
    /**
     * save data
     * @param array request
     */
    public function save($request){

        $validator = Validator::make($request->all(),[                                    
            'phone' => 'required|max:13',
            'country' => 'required',
            'phone_type' => 'required',
            //'group' => 'required',

        ]);
       
        
        if ($validator->fails()){
            return $this->processServiceResponse(false, $validator->errors()->first(),null);                      
        }
        

        $group = $request->input('group');
        $phone = $request->input('phone');

        if(strlen($phone)==10){
            $phone = '1'.$phone;
        }
        $obj = Contact::where('phone','=',$phone)->where('account_id','=',$this->account_id)->count();
        if($obj){
            return $this->processServiceResponse(false, 'This phone number already exist',null);
        }

        // Create or Update 
        $dataObj =  new Contact;
        
        // $dataObj->id = strrev(strtotime(date("Y-m-d H:i:s")));
        $contactId = $this->genContactId();
        $dataObj->id = $contactId;
        $dataObj->user_id = Auth::user()->userid;
        $dataObj->account_id = $this->account_id;
        if(empty($request->input('first_name'))){
            $dataObj->first_name = $phone;
        }else{
            $dataObj->first_name = $request->input('first_name');
        }
        if(!empty($request->input('last_name'))){
            $dataObj->last_name = $request->input('last_name');
        }else{
            $dataObj->last_name = '';
        }
        //$dataObj->group_id = (!empty($group['code'])) ? $group['code'] : '';
        $dataObj->phone = $phone;
        $dataObj->country = $request->input('country');
        if(!empty($request->input('company'))){
            $dataObj->company = $request->input('company');
        }else{
            $dataObj->company = '';
        }
        //$dataObj->status = config('dashboard_constant.PENDING');
        $dataObj->contact_type = 'S';
        $dataObj->phone_type = $request->input('phone_type');

        if($dataObj->save()) {
            //$this->DidService->save($request);
            $this->AuditLogService->createLog($dataObj, 'A');
            if(!empty($group)){
                foreach ($group as $key => $value) {
                    $this->addContactGroup($this->account_id, $value['code'], $contactId);
                    $updateList = Group::find($value['code']);
                    if($updateList){
                        $updateList->num_contacts = $updateList->num_contacts + 1;
                        $updateList->save();
                    }
                }
            }
            return $this->processServiceResponse(true, "Contact Added Successfully!",$dataObj);
        }
        return $this->processServiceResponse(false, "Contact Added Failed!",$dataObj);
    }

    /**
     * GENERATE RANDOM CONTACT ID
     */
    public function genContactId(){
        $id = $this->genRandNum(10);
        $IdExists = Contact::find($id); 
        if($IdExists){
            return $this->genContactId();
        }
        return $id;
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
     * get contact details
     * $param int $id
     */
    public function getContactDetailById($id){
        //Get detail
        return Contact::findOrFail($id); 
    }

    /**
     * get details
     * $param int $id
     */
    public function getDetail($id){
        //Get detail
        return Contact::with('group')->findOrFail($id); 
    }

    /**
     * get contact details
     * $param int $id
     */
    public function getContactDetail($id){
        //Get detail
        $data = array();
        $contact = Contact::findOrFail($id); 
        if($contact){
            $groups = \DB::table('sms_contact_group')
                            ->join('contact_group', 'sms_contact_group.id', '=', 'contact_group.group_id')
                            ->where('contact_group.account_id','=', $this->account_id)
                            ->where('contact_group.contact_id','=', $id)
                            ->select('sms_contact_group.*')
                            ->get();
            $data['groups'] = $groups;
        }
        $data['contact'] = $contact;
        return $data; 
    }

    /**
     * get Contact Group
     * $param string $account_id
     * $param string $group_id
     * $param string $contactId
     */
    public function addContactGroup($account_id, $group_id, $contactId){
        $obj = new ContactGroup();
        $obj->account_id = $account_id;
        $obj->contact_id = $contactId;
        $obj->group_id = $group_id;
        $obj->save();
    }

    /**
     * update data
     * @param array request
     */
    public function updateData($request){
        $validator = Validator::make($request->all(),[
            //'first_name' => 'required|string|max:30',
            //'last_name' => 'required|string|max:30',
            //'phone' => 'required|digits:11',
            'phone' => 'required|max:13',
            //'company' => 'required',
            'country' => 'required',
            'phone_type' => 'required',

        ]);

        if ($validator->fails()){ 
            return $this->processServiceResponse(false, $validator->errors()->first(),null);           
        }

        $group = $request->input('group');        
        $phone = $request->input('phone');

        if(strlen($phone)==10){
            $phone = '1'.$phone;
        }

        $obj = Contact::where('phone','=',$phone)
                        ->where('account_id','=',$this->account_id)
                        ->where('id','!=',$request->id)
                        ->count();

        if($obj){
            return $this->processServiceResponse(false, 'This phone number already exist',null);
        }
        
        // get detail
        $dataObj = $this->getContactDetailById($request->id);
        $contactId = $request->id;
        $didUpdatePhone = $dataObj->phone;
        
        if(empty($request->input('first_name'))){
            $dataObj->first_name = $phone;
        }else{
            $dataObj->first_name = $request->input('first_name');
        }
        if(!empty($request->input('last_name'))){
            $dataObj->last_name = $request->input('last_name');
        }else{
            $dataObj->last_name = '';
        }
        //$dataObj->group_id = $group['code'];        
        $dataObj->phone = $phone;
        $dataObj->country = $request->input('country');
        if(!empty($request->input('company'))){
            $dataObj->company = $request->input('company');
        }else{
            $dataObj->company = '';
        }
        $dataObj->phone_type = $request->input('phone_type');
        $dataObjU = $dataObj->getDirty();

        if($dataObj->save()) {
            //$this->DidService->updateData($didUpdatePhone, $dataObj);
            $this->updateGroupContactNumber($this->account_id, $request->id);
            $this->deleteContactFromGroup($this->account_id, $request->id);
            if(!empty($group)){
                foreach ($group as $key => $value) {
                    $this->addContactGroup($this->account_id, $value['code'], $contactId);
                    $updateList = Group::find($value['code']);
                    if($updateList){
                        $updateList->num_contacts = $updateList->num_contacts + 1;
                        $updateList->save();
                    }
                }
            }
            $this->AuditLogService->createLog($dataObjU, 'U');
            return $this->processServiceResponse(true, "Contact Update Successfully!",$dataObj);
        }
        return $this->processServiceResponse(false, "Contact Update Failed!",$dataObj);

    }

    /**
     * import data
     * @param array request
     */
    public function import($request){

        $uploadedFile = $request->file('file');        

        $validator = Validator::make($request->all(),[
            'file' => 'required',
            'file' => 'mimes:csv,txt'
        ]);

        if ($validator->fails()){
            return $this->processServiceResponse(false, $validator->errors()->first(),null);       
        }

        $originalFilename = $uploadedFile->getClientOriginalName();
        $filename = time().$uploadedFile->getClientOriginalName();
        \Storage::disk('local')->putFileAs(
            'files/'.$filename,
            $uploadedFile,
            $filename
          );

            $ListArray = array();

            $handle = fopen(storage_path('app/files/'.$filename.'/'.$filename), "r");
            $header = false;
            $readLine = 0;
            $maxColumns = 0;
            $lineNumber = 0;

            while ($csvLine = fgetcsv($handle, 1000, ",")) {

                if($lineNumber==10){
                    break;
                }
                if ($header) {
                    $header = false;
                } else {
                    $ListArray[] = $csvLine;                    
                }     
                $lineNumber++;           
            }
            //array_pop($ListArray);
            if(empty($ListArray)){                
                return $this->processServiceResponse(false, "File is Empty",[]);
            }

            $maxColumns = count($ListArray[0]);

            $data['importJsonData'] = $this->getUserImportJsonFile(); 
            $data['fileName'] = $filename;
            $data['originalFilename'] = $originalFilename;
            $data['fieldName'] = array("noimport" => "Do not import","first_name" => "First Name", "last_name" => "Last Name", "phone" => "Phone", "company" => "Company");
            $data['contacts'] = $ListArray;
            $data['maxColumns'] = $maxColumns;

            return $this->processServiceResponse(true, "", $data);

                
    }

    /**
     * insert import data
     * @param array data
     * @param string $key
     */
    public function unique_multidim_array($array, $key) { 
        $temp_array = array(); 
        $i = 0; 
        $key_array = array(); 
        foreach($array as $val) { 
            if (!in_array($val[$key], $key_array)) { 
                $key_array[$i] = $val[$key]; 
                $temp_array[$i] = $val; 
            } 
            $i++; 
        } 
        return $temp_array; 
    } 

    /**
     * insert import data
     * @param array request
     */
    public function importCreate($request){

            $fileName = $request->input('fileName');
            $fieldName = $request->input('fieldName');
            $originalFilename = $request->input('originalFilename');
            $excludeFirstRow = $request->input('excludeFirstRow');
            $matchColumns = $request->input('matchColumns');                               

            array_shift($fieldName);            
            if(empty($fieldName)){                
                return $this->processServiceResponse(false, "All Fields are Empty",[]);
            }


            $filePath = storage_path('app/files/'.$fileName.'/'.$fileName);
            $handle = fopen($filePath, "r");
            $header = ($excludeFirstRow) ? true : false;
            $min = 0;
            $jsonLogPath = "Import-".Auth::user()->userid.".json";
            $contactArray = array();
            $insertArray = array();
            $failedArray = array();
            $josnLogArray = array();

            $i=0;
            while ($csvLine = fgetcsv($handle, 1000, ",")) {

                if($i!=0) {
                    if ($header) {
                        $header = false;
                    } else {
                        $contactArray[] = $csvLine;                    
                    }
                }


                $i++;        
            }

            if(empty($contactArray)){  
                return $this->processServiceResponse(false, "File is Empty",[]);
            }

            //create group
            $groupId = '';
            $groupId = $this->insertGroup($originalFilename);

            foreach($contactArray as $key => $value){
                if(count($fieldName) == count($value)){
                    if($this->validatePhone(array_combine($fieldName, $value))) {
                    // if(true){
                        $insertArray[$key] = array_merge($this->getAdditionalFields($groupId),array_combine($fieldName, $value));
                        unset($insertArray[$key]['noimport']);                        
                        unset($insertArray[$key-1]['noimport']);
                    }
                }
            }

            $totalCount = count($contactArray)-1;
            // remove duplicate phone number
            $insertArray = $this->unique_multidim_array($insertArray,'phone');
            //var_dump($insertArray);
            // die();              
            // remove file
            if(file_exists($filePath)){
                @unlink($fileName);                
            }
            // write josn log file
            $josnLogArray['fieldName'] = $fieldName;
            $josnLogArray['excludeFirstRow'] = $excludeFirstRow;
            $josnLogArray['matchColumns'] = $matchColumns;              
            \Storage::disk('local')->put('files/'.$jsonLogPath, json_encode($josnLogArray));
            
            // var_dump($insertArray);
            //die();
            
            if(empty($insertArray)){
                \DB::table('sms_contact_group')->where('id', $groupId)->delete();         
                return $this->processServiceResponse(false, "Failed to Import Contacts",[]);
            }
            // insert contacts
            $status = \DB::table('contacts')->insert($insertArray);            
            if($status){
                $this->updateGroup($groupId, count($insertArray));
                $this->addImportContactGroup($insertArray,$groupId,$this->account_id);
                return $this->processServiceResponse(true, "Successfully Imported (".count($insertArray).") Contacts out of (".$totalCount.")",['groupId'=> $groupId]);
            }

            \DB::table('sms_contact_group')->where('id', $groupId)->delete();
            return $this->processServiceResponse(false, "File is Empty",[]);
    }
    /**
     * get user import json file data
     */
    public function getUserImportJsonFile(){        
        $filePath = storage_path('app/files/'."Import-".Auth::user()->userid.".json");
        if(file_exists($filePath)){            
            $json = json_decode(file_get_contents($filePath), true);
            return $json;
        }
        return [];
    }

    public function addImportContactGroup($insertArray,$group_id,$account_id){        
        foreach ($insertArray as $key => $value) {
            $obj = new ContactGroup();
            $obj->account_id = $account_id;
            $obj->contact_id = $value['id'];
            $obj->group_id = $group_id;
            $obj->save();
        }
    }

    public function validatePhone($data) {

        if(isset($data['phone'])){
            $data['phone'] = trim($data['phone'],'+');
            if(is_numeric($data['phone'])){
                if(strlen($data['phone'])!=10) {
                    return false;                    
                }
                if($this->checkIfPhoneExist($data['phone'])){
                    return false;
                }
                return true;
            }
        }        
        return false;
    }

    public function checkIfPhoneExist($phone){        
        $obj = Contact::where('phone','=',$phone)
                        ->where('account_id','=',$this->account_id)                        
                        ->count();
        if($obj){
            return true;
        }
        return false;
    }
    /**
     * update group number of contacts
     * @param string $groupId
     * @param string $numContacts 
     */
    public function updateGroup($groupId, $numContacts){
        \DB::table('sms_contact_group')
        ->where('id', $groupId)
        ->limit(1)
        ->update(array('num_contacts' => $numContacts));
    }
    /**
     * create new group by filename
     * @param string $filename
     */
    public function insertGroup($filename){
            $list = new Group;
            $listid = $this->genListId();
            $list->id = $listid;
            $list->name = date('m-d-y').'-'.$filename;
            $list->account_id = $this->getAccountId();
            $list->user_id = \Auth::user()->userid;
            $list->num_contacts = 0;
            $list->created_by = \Auth::user()->userid;
            $list->updated_by = \Auth::user()->userid;
            $list->save();
            $list->id = $listid;
            $this->AuditLogService->createLog($list, 'A');

            return $listid;
    }

    public function getAdditionalFields($groupId){
        return array(
            'id' => $this->genContactId(),
            'user_id' => \Auth::user()->userid,
            'account_id' => \Auth::user()->account_id,            
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
            'group_id' => $groupId
        );
    }

    /**
     * import data
     * @param array request
     */
    public function __import($request){

        $uploadedFile = $request->file('file');        

        $validator = Validator::make($request->all(),[
            'file' => 'required',
            'file' => 'mimes:csv,txt'
        ]);

        if ($validator->fails()){
            return $this->processServiceResponse(false, $validator->errors()->first(),null);       
        }

        $filename = time().$uploadedFile->getClientOriginalName();
        \Storage::disk('local')->putFileAs(
            'files/'.$filename,
            $uploadedFile,
            $filename
          );

          $ListArray = array();

            $handle = fopen(storage_path('app/files/'.$filename.'/'.$filename), "r");
            $header = true;

            while ($csvLine = fgetcsv($handle, 1000, ",")) {

                if ($header) {
                    $header = false;
                } else {
                    $ListArray[] = $csvLine;
                }
            }
            array_pop($ListArray);
            if(empty($ListArray)){
                return $this->processServiceResponse(false, "File is Empty",null);
            }

            //validate
            foreach ($ListArray as $key => $value){
                $phone = trim($value[2], ' ');
                $phone_type = trim($value[5], ' ');
                $company = trim($value[4], ' ');
                if(strlen($phone)!=11){
                    return $this->processServiceResponse(false, "Phone must be 11 in length on line ".$key,null);
                }
                if(empty($value[0])){
                    $value[0] = $phone;                    
                }
                // if(empty($value[1])){
                //     return [
                //         config('msg_label.MSG_RESULT') => false,
                //         config('msg_label.MSG_MESSAGE') => "Last Name is required ".$key,
                //         config('msg_label.MSG_DATA') => null
                //     ];
                // }
                if(empty($value[2])){
                    return $this->processServiceResponse(false, "Phone is required ".$key,null);
                }
                if(empty($value[3])){
                    return $this->processServiceResponse(false, "Company is required ".$key,null);                 
                }
                if(empty($company)){
                    return $this->processServiceResponse(false, "Country is required ".$key,null);
                }
                if(empty($phone_type)){
                    return $this->processServiceResponse(false, "Phone Type is required ".$key,null);
                }
            }

            $list = new Group;
            $listid = $this->genListId();
            $list->id = $listid;
            $list->name = $filename;
            $list->account_id = $this->getAccountId();
            $list->user_id = \Auth::user()->userid;
            $list->num_contacts = 0;
            $list->created_by = \Auth::user()->userid;
            $list->updated_by = \Auth::user()->userid;
            if($list->save()){

                foreach ($ListArray as $key => $value) {             

                    $contact = new Contact;
                    $contact->id = $this->genContactId();
                    $contact->first_name = $value[0];
                    $contact->last_name = $value[1];
                    $contact->group_id = $listid;
                    $contact->user_id = \Auth::user()->userid;
                    $contact->phone = trim($value[2], ' ');
                    $contact->company = $value[3];
                    $contact->country = trim($value[4], ' ');
                    $contact->phone_type = trim($value[5], ' ');          
                    $contact->contact_type = 'S';
                    $contact->save();                    
                }
                $updateList = Group::find($listid);
                $updateList->num_contacts = count($ListArray);
                $updateList->save();

                return $this->processServiceResponse(true, "Contact Import Successful!",$list);

            }
            return $this->processServiceResponse(false, "Contact Import Failed!",$list);    
    }

    /**
     * update group contact number
     * @param int $id
     */
    public function updateGroupContactNumber($account_id, $contact_id){
        $dataObj = ContactGroup::where('account_id','=',$account_id)
                            ->where('contact_id','=',$contact_id)
                            ->get();
        foreach ($dataObj as $key => $value) {
            $obj = Group::find($value->group_id);
            if($obj){
                $obj->num_contacts = $obj->num_contacts - 1;
                $obj->save();
            }
        }
    }

     /**
     * delete contact from group selected all
     * @param int $id
     */
    public function deleteContactFromGroupSelectedAll($request, $group_id){

        $obj = ContactGroup::where('account_id','=',$this->account_id)
                            ->where('group_id','=',$group_id)                            
                            ->delete();
        $objData = Group::find($group_id);
        if($objData){
            $objData->num_contacts = 0;
            $objData->save();
        }
        
        if($obj){
            return $this->processServiceResponse(true, "Contact Successfully Deleted!",$obj);
        }
        return $this->processServiceResponse(false, "Contact Deleted Failed!",$obj);        
    }

    /**
     * delete contact from group selected
     * @param int $id
     */
    public function deleteContactFromGroupSelected($request, $group_id){
        $ids = $request->input('ids');        
        $obj = ContactGroup::where('account_id','=',$this->account_id)
                            ->where('group_id','=',$group_id)
                            ->whereIn('contact_id',$ids)
                            ->delete();
        foreach ($ids as $key => $value) {
            $objData = Group::find($group_id);
            if($objData){
                $objData->num_contacts = $objData->num_contacts - 1;
                $objData->save();
            }
        }
        
        if($obj){
            return $this->processServiceResponse(true, "Contact Successfully Deleted!",$ids);
        }
        return $this->processServiceResponse(false, "Contact Deleted Failed!",$ids);        
    }

    /**
     * delete contact from group
     * @param int $id
     */
    public function deleteContactFromGroup($account_id, $contact_id){
        $obj = ContactGroup::where('account_id','=',$account_id)
                            ->where('contact_id','=',$contact_id)
                            ->delete();
    }

    /**
     * delete contact
     * @param int $id
     */
    public function deleteContact($contact_id,$group_id){
        $obj = ContactGroup::where('account_id','=',$this->account_id)
                            ->where('contact_id','=',$contact_id)
                            ->where('group_id','=',$group_id)
                            ->delete();
        if($obj){
            $group = Group::find($group_id);
            if($group){
                $group->num_contacts = $group->num_contacts - 1;
                $group->save();
            }
        }                            
        return $this->processServiceResponse(true, "Contact Successfully Deleted!",$obj);
    }

    /**
     * delete data
     * @param int $id
     */
    public function delete($id){
        $contactId = $id;
        $dataObj = $this->getContactDetailById($id);
        $group_id = $dataObj->group_id;        
       
        if($dataObj->delete()) {
            $this->AuditLogService->createLog($dataObj, 'D');
            $this->updateGroupContactNumber($this->account_id,$contactId);
            $this->deleteContactFromGroup($this->account_id,$contactId);
            
            return $this->processServiceResponse(true, "Contact Deleted Successfully!",$dataObj);            
        }
        return $this->processServiceResponse(false, "Contact Deleted Failed!",$dataObj);
    }

    /**
     * delete contact by Group Id
     * @param char groupId
     */
    public function deleteContactsByGroupId($group_id){
        $obj = ContactGroup::where('account_id','=',$this->account_id)
                            ->where('group_id','=',$group_id)
                            ->delete();
        return $obj;
    }

    /**
     * search from contact table
     * @param char queryParam
     */
    public function getSearchContactsData($queryParam){  
        $account_id = \Auth::user()->account_id;
        $user_id = \Auth::user()->userid;        
        $query = Contact::where('account_id', '=', $account_id)->where('user_id', '=', $user_id)->select(\DB::raw('contacts.phone AS code, CONCAT(COALESCE(contacts.first_name,""), " ", COALESCE(contacts.last_name,""), " (",contacts.phone,")") AS value'));
        if(isset($queryParam['q']) && !empty($queryParam['q'])){
            $query->Where(function($query) use ($queryParam) {
                return $query->orWhere('phone', "LIKE","%".$queryParam['q']."%")
                             ->orWhere('first_name', "LIKE","%".$queryParam['q']."%")
                             ->orWhere('last_name', "LIKE","%".$queryParam['q']."%");
            });
        }
        
        $list = $query->limit(10)->get()->toArray();
        return $list;
    }

    /**
     * search from group table
     * @param char queryParam
     */
    public function getSearchGroupData($queryParam){        
        $account_id = \Auth::user()->account_id;
        $query = Group::where('account_id', '=', $account_id)->select(\DB::raw('sms_contact_group.id AS code, CONCAT(COALESCE(sms_contact_group.name,""), " (", sms_contact_group.num_contacts, ")") as value'));
        if(isset($queryParam['q']) && !empty($queryParam['q'])){
            $query->where('name', "LIKE","%".$queryParam['q']."%");
        }
        $list = $query->limit(10)->get()->toArray();
        return $list;
    }

    /**
     * search for contact based on contact number and group name
     * @param array request
     */
    public function getSearchData($request){
        $queryParam = $request->query();
        $data1 = $this->getSearchContactsData($queryParam);        
        $data2 = $this->getSearchGroupData($queryParam);        
        $data = array_merge($data1,$data2);
        return $data; 
    }

}
