<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Hash;
use DB;
use Auth;
use Session;

class AuditLogService extends AppService
{

    public function __construct()
    {
        $this->account_id = Auth::user() ? Auth::user()->account_id : '';
        $this->extn = Auth::user() ? Auth::user()->extn : '';
    }
    
    /**
     * get pagination data
     */
    public function getPagination($request){
        // Get list        
        $startTime = date('Y-m-d', strtotime('today - 30 days'))." 00:00";
        $endTime = date('Y-m-d')." 23:59";
        $maxDateDiff = config('dashboard_constant.REPORT_MAX_DATE_DIFF');

        $queryParam = $request->query();

        $query = AuditLog::where('account_id','=', $this->account_id)->where('extn','=', $this->extn);

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
        }else if($stdate !== false){
            $startTime = $queryParam['start_time'];
            $endTime = date('Y-m-d',strtotime($queryParam['start_time']))." 23:59";
        }

        $query->whereBetween("audit_log.date_time",[$startTime,$endTime]);
        

        if(isset($queryParam['user_name']) && !empty($queryParam['user_name']) ){
            $query->where("audit_log.user_name",$queryParam['user_name']);            
        }
        if(isset($queryParam['ip']) && !empty($queryParam['ip']) ){
            $query->where("audit_log.ip",$queryParam['ip']);            
        }

        $per_page = (isset($queryParam['per_page']) && !empty($queryParam['per_page']) && $queryParam['per_page']!='undefined') ? $queryParam['per_page'] : config('dashboard_constant.PAGINATION_LIMIT');

        $query = $query->orderBy('audit_log.date_time', 'DESC')->paginate($per_page);
        foreach($query as $key => $value){
            $value->changed_value = json_decode($value->changed_value);
        }
        return $this->paginationDataFormat($query->toArray());
    }

    /**
     * create Log
     */
    public function createLog($request, $changed_type, $type=false){
        $log = new AuditLog;        
        $log->account_id = \Auth::user()->account_id;
        if($type){
            $log->user_id = 'admin';
            $log->user_name = 'admin';
            $log->extn = '';
        }else{
            $log->user_id = \Auth::user()->userid;
            $log->user_name = \Auth::user()->fname;
            $log->extn = \Auth::user()->extn;
        }
        $log->date_time = $this->dateToTimestamp(date('Y-m-d H:i:s'));
        $log->page_URI = $_SERVER['REQUEST_URI'];
        $log->ip = $_SERVER['REMOTE_ADDR'];
        $log->changed_type = $changed_type;
        $log->changed_value = json_encode($request);

        if($log->save()) {
            return $this->processServiceResponse(true, "Log Added Successfully!", $log);           
        }

        return $this->processServiceResponse(false, "Log Added Failed!", $log);
    }

    /**
     * create Admin Log
     */
    public function createAdminLog($request, $changed_type){
        $log = new AuditLog;        
        $log->account_id = \Auth::guard('admin')->user()->account_id;
        $log->user_id = 'admin';
        $log->user_name = 'admin';
        $log->extn = '';
        $log->date_time = $this->dateToTimestamp(date('Y-m-d H:i:s'));
        $log->page_URI = $_SERVER['REQUEST_URI'];
        $log->ip = $_SERVER['REMOTE_ADDR'];
        $log->changed_type = $changed_type;
        $log->changed_value = json_encode($request);

        if($log->save()) {
            return $this->processServiceResponse(true, "Log Added Successfully!", $log);           
        }

        return $this->processServiceResponse(false, "Log Added Failed!", $log);
    }

}
