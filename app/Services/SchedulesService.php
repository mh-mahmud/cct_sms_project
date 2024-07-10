<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\User;
use App\Models\Log;
use App\Models\Contact;
use App\Models\Schedule;
use App\Models\ScheduleContact;
use App\Models\ContactGroup;
use Illuminate\Support\Facades\Hash;
use App\Services\ScheduleContactsService;
use App\Services\AuditLogService;
use Validator;
use DB;
use Auth;

class SchedulesService extends AppService
{

    public function __construct()
    {
        $this->ScheduleContactsService = new ScheduleContactsService();
        $this->AuditLogService = new AuditLogService();
        $this->account_id = Auth::user()->account_id;
        $this->extn = Auth::user()->extn;
        $this->did = Auth::user()->did;
    }
    
    /**
     * get pagination data
     */
    public function getPagination($request){
        // Get list
        $hasQuery = false;
        $startTime = date('Y-m-d', strtotime('today - 30 days'))." 00:00";
        $endTime = date('Y-m-d')." 23:59";
        $maxDateDiff = config('dashboard_constant.REPORT_MAX_DATE_DIFF');

        $queryParam = $request->query();

        $query = Schedule::where('account_id','=', $this->account_id)->where('sms_from','=', $this->did);

        $stdate = isset($queryParam['start_time']) ? \DateTime::createFromFormat('Y-m-d H:i', $queryParam['start_time']) : false;        
        $endate = isset($queryParam['end_time']) ? \DateTime::createFromFormat('Y-m-d H:i', $queryParam['end_time']): false;
        if($stdate !== false && $endate !== false){            
            $startTime = $queryParam['start_time'];
            $endTime = $queryParam['end_time'];
            $diff = date_diff($stdate,$endate);
            $daysDiff = $diff->format("%a");
            if($daysDiff > $maxDateDiff){                
                // add (REPORT_MAX_DATE_DIFF) days to start time
                $stdate->modify('+'.$maxDateDiff.' days');
                $endTime = $stdate->format('Y-m-d');
            }
            $hasQuery = true;
        }else if($stdate !== false){            
            $startTime = $queryParam['start_time'];
            $endTime = date('Y-m-d',strtotime($queryParam['start_time']))." 23:59";
            $hasQuery = true;
        }
              

        //$query->whereBetween("start_time",[$startTime,$endTime]);

        if(isset($queryParam['status']) && !empty($queryParam['status']) ){            
            $query->where("status",$queryParam['status']);
            $hasQuery = true;
        }

        $query = $query->where('is_schedule','=', '1');

        //get last 30 days
        if(!$hasQuery){
            $startTime = date('Y-m-d', strtotime('today - 30 days'))." 00:00";
            $endTime = date('Y-m-d')." 23:59";
            //$query->whereBetween("start_time",[$startTime,$endTime]);
        }else{
            if($stdate !== false && $endate !== false){
                // echo $tzos = $this->getTimeZoneOffset(config('app.timezone'));
                // echo $startTime = date('Y-m-d H:i:s', strtotime($startTime)+$tzos);
                // echo $endTime = date('Y-m-d H:i:s', strtotime($endTime)+$tzos);
                $startTime = date('Y-m-d',strtotime($startTime))." 00:00";
                $endTime = date('Y-m-d',strtotime($endTime . "+1 days"))." 23:59";                
                $query->whereBetween("start_time",[$startTime,$endTime]);
            }
        }

        $per_page = (isset($queryParam['per_page']) && !empty($queryParam['per_page']) && $queryParam['per_page']!='undefined') ? $queryParam['per_page'] : config('dashboard_constant.PAGINATION_LIMIT');

        $query = $query->orderBy('start_time', 'DESC')->paginate($per_page); 
        
        $authUser = \Session::get('loginUser');
        $tz_offset = 0;$tz = '';
        foreach($query as $key => $value){
            $tz = (!empty($value->time_zone)) ? $value->time_zone : $authUser['timezone'];
            // $tz_offset = $this->getTimeZoneOffset($tz);
            // $value->start_time=date('Y-m-d H:i:s', strtotime($value->start_time)+$tz_offset);            
            $value->start_time=$this->convertTime(config('app.timezone'), $tz, $value->start_time);
            $value->sms_text = nl2br($value->sms_text);
        }        
        return $this->paginationDataFormat($query->toArray());
    }
    /**
     * save data
     * @param array request
     */
    public function save($request){

        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:30',
            'message' => 'required|max:420',
        ]);

        if ($validator->fails()){
            return $this->processServiceResponse(false, $validator->errors()->first(),null);           
        }
        

        // Create or Update 
        $dataObj =  new Template;
        
        // $dataObj->id = strrev(strtotime(date("Y-m-d H:i:s")));
        $dataObj->id = $this->genTemplateId();
        $dataObj->account_id = $this->getAccountId();
        $dataObj->name = $request->input('name');
        $dataObj->message = $request->input('message');
        $dataObj->created_by = \Auth::user()->userid;
        $dataObj->updated_by = \Auth::user()->userid;

        if($dataObj->save()) {
            return $this->processServiceResponse(true, "Template Added Successfully!",$dataObj);
        }
        return $this->processServiceResponse(false, "Template Added Failed!",$dataObj);
    }

    /**
     * GENERATE RANDOM TEMPLATE ID
     */
    public function genTemplateId(){
        $id = $this->genPrimaryKey();        
        $IdExists = Template::find($id);        
        if($IdExists){
            return $this->genTemplateId();
        }
        return $id;
    }
    /**
     * GENERATE RANDOM SCHEDULE CONTACT ID
     */
    public function genScheduleContactId(){
        $id = $this->genRandNum(10); 
        $idExists = ScheduleContact::find($id); 
        if($idExists){
            return $this->genScheduleContactId();
        }
        return $id;
    }

    

    /**
     * get details
     * $param int $id
     */
    public function getDetail($id){
        //Get detail
        return Schedule::findOrFail($id); 

    }

    /**
     * update data
     * @param array request
     */
    public function updateData($request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:30',
            'message' => 'required|max:420',
        ])->validate();
        
        // get detail
        $dataObj = $this->getDetail($request->id);
        
        //$dataObj->account_id = $this->getAccountId();
        $dataObj->name = $request->input('name');
        $dataObj->message = $request->input('message');        
        $dataObj->updated_by = \Auth::user()->userid;

        if($dataObj->save()) {
            return $this->processServiceResponse(true, "Template Update Successfully!",$dataObj);
        }
        return $this->processServiceResponse(false, "Template Update Failed!",$dataObj);
    }

    /**
     * delete data
     * @param int $id
     */
    public function delete($id){
        $dataObj = $this->getDetail($id);
       
        if($dataObj->delete()) {
            
            if($this->ScheduleContactsService->deleteContactsByScheduleId($id)){
                return $this->processServiceResponse(true, "Schedule Deleted Successfully!", $dataObj);
            }          
        }

        return $this->processServiceResponse(false, "Schedule Deleted Failed!", $dataObj);   
    }


    /**
     * detail data
     * @param int $id
     */
    public function getScheduleDetail($id, $withbr = false){
        $dataObj = Schedule::with('contacts')->find($id);        
        $dataObj->groupContacts = $dataObj->getGroupContacts($dataObj);
        if($withbr)
            $dataObj->sms_text = nl2br($dataObj->sms_text);
        return $dataObj;
    }

    /**
     * detail data
     * @param int $id
     */
    public function getScheduleDetailForCompose($id, $withbr = false){
        $dataObj = Schedule::find($id);
        $dataObj->contacts = $dataObj->getContacts($id);
        $dataObj->groupContacts = $dataObj->getContactsByGroupId($id);
        if($withbr)
            $dataObj->sms_text = nl2br($dataObj->sms_text);
        return $dataObj;
    }
    /**
     * save schedule contacts
     * @param array $reqData
     * @param str $colname
     */
    public function saveScheduleContacts($reqData, $colname){ 
        $contacts = $reqData[$colname]; 
        if(!empty($contacts)){
            unset($reqData[$colname]);
            $insData = [];
            foreach($contacts as $key => $val){
                $reqData['id'] = $this->genScheduleContactId();
                $insData[$key] = array_merge([$colname => $val],$reqData);
            }            
            $result = ScheduleContact::insert($insData);
            if($result){
                return $this->processServiceResponse(true, "Compose Save Successfully!",$reqData);
            }
        }
        return $this->processServiceResponse(false, "Compose Save Failed!",$reqData);
    }
    
    
    /**
     * update schedule number contacts
     * @param int $id 
     * @param int $totalCon (total number of contacts)
     */
    public function updateTotalContacts($id, $conArr){
        $groups;$totalContacts = 0;        
        foreach($conArr['group'] as $key => $value){
            //$groups = Contact::where('group_id', '=', $value)->get();
            $groups = ContactGroup::where('account_id', '=', $this->account_id)
                                    ->where('group_id', '=', $value)->get();
            $totalContacts = $totalContacts + $groups->count();
        }
        $totalContacts = $totalContacts + count($conArr['single']);
        $res = Schedule::where('id',$id)->increment('num_contacts', $totalContacts);
        if($res){
            $this->AuditLogService->createLog(array('num_contacts' => $totalContacts), 'U');
        }
        return $this->processServiceResponse($res ? true : false, $res ? "Update Successfully!" : "Update Failed!",$totalContacts);
    }

    /**
     * update schedule status
     * @param int $id
     * @param string $status 
     */
    public function changeScheduleStatus($log_time, $account_id, $did, $client_number, $callid, $status){
        
        $res = Log::where('log_time',$log_time)
                    ->where('account_id',$account_id)
                    ->where('did',$did)
                    ->where('client_number',$client_number)
                    ->where('callid',$callid)
                    ->update(['status' => $status]);
        if($res){
            $this->AuditLogService->createLog(array('status' => $status), 'U');
        }
        return $this->processServiceResponse($res ? true : false, $res ? "Update Successfully!" : "Update Failed!",$status);
    }
    

    /**
     * delete schedule contacts by Schedule Id
     * @param int $id     
     */
    public function deleteScheduleContacts($schedule_id){        
        $res = ScheduleContact::where('schedule_id',$schedule_id)->delete();

        return $this->processServiceResponse($res ? true : false, $res ? "Delete Successfully!" : "Delete Failed!",$schedule_id);
    }

}
