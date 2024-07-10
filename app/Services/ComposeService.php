<?php

namespace App\Services;


use Illuminate\Http\Request;
use App\User;
use App\Models\Contact;
use App\Models\Compose;
use App\Models\Template;
use App\Models\Queue;
use App\Models\ScheduleContact;
use App\Models\Schedule;
use App\Models\Group;
use App\Models\Did;
use DB;
use Auth;
use Session;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Services\SchedulesService;
use App\Services\AuditLogService;
use App\Services\UsersService;

class ComposeService extends AppService
{
    public function __construct()
    {
        $this->SchedulesService = new SchedulesService();
        $this->AuditLogService = new AuditLogService();
        $this->UsersService = new UsersService();
        $this->extn = Auth::user()->extn;
        $this->did = Auth::user()->did;
        $this->user_id = Auth::user()->userid;
    }
    /**
     * get pagination data
     */
    public function getPagination(){
        // Get list
        $data = Contact::orderBy('created_at', 'DESC')->paginate(config('dashboard_constant.PAGINATION_LIMIT')); 
        return $this->paginationDataFormat($data->toArray());
    }
    /**
     * save data
     * @param array request
     */
    public function save($request){        
        
        $rules = [
            'to' => 'required',
            'from' => 'required|max:13',
            'message' => 'required|max:480',
        ];
        $authUser = Session::get('loginUser');

        $scheduleShow = $request->input('scheduleShow');
        if($scheduleShow){
            $rules['scheduleDate'] = 'required|after:'.date('Y-m-d');            
        }        
        Validator::make($request->all(),$rules)->validate();

        
        $smsTo = $request->input('to');        
        $time_zone = $request->input('time_zone'); 
        foreach($smsTo as $key => $value){
            if(!isset($value['code'])){
                $smsTo[$key]['code'] = $value['value'];
            }
        }
        if(!$this->hasBalance()){
            return $this->processServiceResponse(false, 'Balance is insufficient.Please talk to your management for top-up.',null);
        }
        $commit = true;
	    $from_number = $request->input('from');
        $from_number = substr($from_number, -10);
        if(strlen($from_number)!=10){
            return $this->processServiceResponse(false, 'SMS From Number is Invalid.',null);
        }
        $from_number = '1' . $from_number;
        $request->merge(['from' => $from_number]);
                
            DB::beginTransaction();
            try {
            
                $scheduleId = $this->genScheduleId();
                $schedule = new Schedule;
                $schedule->id = $scheduleId;
                $schedule->account_id = $this->getAccountId();
                //$schedule->extn = $this->extn;
                $schedule->sms_from = $request->input('from');
                $schedule->sms_text = $request->input('message');
                $schedule->time_zone = $scheduleShow ? $time_zone : '';
                $schedule->start_time = $scheduleShow ? $this->getGMTTime($request->input('scheduleDate'), $time_zone) : $this->dateToTimestamp(date('Y-m-d H:i:s'));
                $schedule->is_schedule = $scheduleShow ? '1' : '0';            
                $schedule->is_repeat = '0';
                $schedule->status = config('dashboard_constant.PENDING');
                $schedule->created_by = \Auth::user()->userid;
                $schedule->updated_by = \Auth::user()->userid;
                if($schedule->save()){
                    $schedule->id = $scheduleId;
                    $this->AuditLogService->createLog($schedule, 'A');
                    $contacts = array_column($smsTo, "value","code");
                    $conArr = $this->getGrpIndContacts($contacts);                
                    $conReqData = [                    
                        'schedule_id' => $scheduleId,
                        'account_id' => $this->getAccountId(),
//                        'ext' => $this->ext,
                        'status' => config('dashboard_constant.PENDING'),
                        'created_by' => \Auth::user()->userid,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    // insert data into schedule contacts table
                    $this->SchedulesService->saveScheduleContacts(array_merge(['group_id' => $conArr['group']],$conReqData) ,'group_id');
                    $this->SchedulesService->saveScheduleContacts(array_merge(['phone' => $conArr['single']],$conReqData) ,'phone');
                    // update total number of contacts of schedule table
                    $this->SchedulesService->updateTotalContacts($scheduleId,$conArr);
                }
                DB::commit();
            } catch (\Throwable $e) {
                DB::rollback();
                throw $e;
                $commit = false;
            }
        
        
        
        $msg = ($scheduleShow) ? "Message Successfully Added to Queue!" : "Message Sent Successfully!";
        return $commit ? $this->processServiceResponse(true, $msg,$request->all()) : $this->processServiceResponse(false, $msg, $request->all());
        
    }
    /**
     * save reply data
     * @param array request
     */
    public function saveReply($request){

        if(!$this->hasBalance()){
            return $this->processServiceResponse(false, 'Balance is insufficient.Please talk to your management for top-up.',null);
        }

        $authUser = Session::get('loginUser');
        $user_time_zone = $authUser['timezone'];
        
        //DB::statement("SET time_zone = '".$user_time_zone."';");

        $smsFrom = $request->input('from');
        $smsTo = $request->input('to');
        $smsText = $request->input('message');

        $scheduleId = $this->genScheduleId();
        $schedule = new Schedule;
        $schedule->id = $scheduleId;
        $schedule->account_id = $this->getAccountId();
        $schedule->ext = $this->ext;
        $schedule->sms_from = $smsFrom;
        $schedule->sms_text = $smsText;
        $schedule->time_zone = '';
        $schedule->num_contacts = 1;
        $schedule->start_time = $this->dateToTimestamp(date('Y-m-d H:i:s'));
        $schedule->is_schedule = '0';    
        $schedule->is_repeat = '0';
        $schedule->status = config('dashboard_constant.PENDING');
        $schedule->created_by = \Auth::user()->userid;
        $schedule->updated_by = \Auth::user()->userid;
        if($schedule->save()){
            $schedule->id = $scheduleId;
            $this->AuditLogService->createLog($schedule, 'A');
            $insData = [
                'id' => $this->SchedulesService->genScheduleContactId(),          
                'schedule_id' => $scheduleId,
                'account_id' => $this->getAccountId(),
                'ext' => $this->ext,
                'phone' => $smsTo,
                'status' => config('dashboard_constant.PENDING'),
                'created_by' => \Auth::user()->userid,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $result = ScheduleContact::insert($insData);
            if($result){
                return $this->processServiceResponse(true, "Reply Sent Successfully!",$insData);
            }
        }
        return $this->processServiceResponse(false, "Reply Sent Failed!",$schedule);        

    }
    /**
     * update data
     * @param array request
     */
    public function updateData($request, $id){
        
        $rules = [
            'to' => 'required',
            'from' => 'required|max:13',
            'message' => 'required|max:480',
        ];

        $scheduleShow = $request->input('scheduleShow');
        $smsTo = $request->input('to');        
        $time_zone = $request->input('time_zone');

        if($scheduleShow){
            $rules['scheduleDate'] = 'required|after:'.date('Y-m-d');
        }        
        Validator::make($request->all(),$rules)->validate();

        $commit = true;
        DB::beginTransaction();
        try {
           
            $scheduleId = $id;
            $schedule = Schedule::find($scheduleId);                      
            $schedule->sms_from = $request->input('from');
            $schedule->sms_text = $request->input('message');
            $schedule->time_zone = $scheduleShow ? $time_zone : '';
            $schedule->start_time = $scheduleShow ? $this->getGMTTime($request->input('scheduleDate'), $time_zone) : $this->dateToTimestamp(date('Y-m-d H:i:s'));
            $schedule->is_schedule = $scheduleShow ? '1' : '0';            
            $schedule->is_repeat = '0';
            $schedule->num_contacts = 0;                       
            $schedule->updated_by = \Auth::user()->userid;
            $scheduleU = $schedule->getDirty();
            if($schedule->save()){
                $this->AuditLogService->createLog($scheduleU, 'U');
                $this->SchedulesService->deleteScheduleContacts($scheduleId);
                $contacts = array_column($smsTo, "value","code");  
                $conArr = $this->getGrpIndContacts($contacts);                
                $conReqData = [                    
                    'schedule_id' => $scheduleId,
                    'account_id' => $this->getAccountId(),
                    'ext' => $this->ext,
                    'status' => config('dashboard_constant.PENDING'),
                    'created_by' => \Auth::user()->userid,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                // insert data into schedule contacts table
                $this->SchedulesService->saveScheduleContacts(array_merge(['group_id' => $conArr['group']],$conReqData) ,'group_id');
                $this->SchedulesService->saveScheduleContacts(array_merge(['phone' => $conArr['single']],$conReqData) ,'phone');
                // update total number of contacts of schedule table
                $this->SchedulesService->updateTotalContacts($scheduleId,$conArr);
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
            $commit = false;
        }
        return $commit ? $this->processServiceResponse(true, "Compose Added Successfully!",$request->all()) : $this->processServiceResponse(false, "Compose Added Failed!", $request->all());
        
    }
    /**
     * get group & individual contact list
     * @param array $contacts
     */
    public function getGrpIndContacts($contacts){ 
        $conArr = ['group'=>[],'single'=>[]];
        array_walk( $contacts,
            function( $val, $key) use (&$conArr) {                
                if($val != $key){                    
                    if(strpos($val, '('.$key.')') > 0){
                        $conArr['single'][] = $key;
                    }else{
                        $conArr['group'][] = $key;
                    }
                }else{                    
                    $conArr['single'][] = $key;
                }
            } 
        ); 
        return $conArr;
    }

    /**
     * CHECK BALANCE
     */
    public function hasBalance(){
        $account_id = $this->getAccountId();
        $obj = $this->UsersService->getAccountInfo($account_id);
        if($obj->credit_amount - $obj->used_amount <=0){
            return false;
        }
        return true;
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
     * GENERATE RANDOM QUEUE ID
     */
    public function genQueueId(){
        $id = $this->genRandNum(10); 
        $idExists = Queue::find($id); 
        if($idExists){
            return $this->genQueueId();
        }
        return $id;
    }

    /**
     * GENERATE RANDOM SCHEDULE ID
     */
    public function genScheduleId(){ 
        $id = $this->genRandNum(10);
        $idExists = Schedule::find($id); 
        if($idExists){
            return $this->genScheduleId();
        }
        return $id;
    }
    /**
     * get Template list
     */
    public function getTemplates(){
        $account_id = $this->getAccountId();        
        $data = Template::where('account_id', '=', $account_id)->get();
        foreach($data as $key => $value){
            $value->omessage = $value->message;
            $value->message = nl2br($value->message);
        }
        return $data->toArray();
    }
    /**
     * get Group list
     */
    public function getGroups(){
        $account_id = $this->getAccountId();        
        $data = Group::where('account_id', '=', $account_id)
                    ->where('user_id', '=', $this->user_id)
                    ->get();
        return $data->toArray();
    }
    /**
     * get Group list
     */
    public function getDid(){
        $account_id = $this->getAccountId();        
        $data = Did::where('account_id', '=', $account_id)
                    ->where('extn', '=', $this->extn)
                    ->pluck('did');
        return $data->toArray();
    }
    /**
     * get Schedule Detail
     *  @param char $id schedule Id
     *  @param bool $withbr sms_text with line breaks
     */
    public function getScheduleDetail($id, $withbr){
        return $this->SchedulesService->getScheduleDetailForCompose($id, $withbr);
    }
    
}
