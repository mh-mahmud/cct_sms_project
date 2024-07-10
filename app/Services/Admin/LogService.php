<?php

namespace App\Services\Admin;

use Illuminate\Http\Request;
use App\User;
use App\Models\Log;
use App\Models\Outbound;
use App\Models\Inbound;
use Illuminate\Support\Facades\Hash;
use DB;
use Validator;
use Auth;
use Session;

class LogService extends AppService
{

    public function __construct()
    {        
        $this->account_id = Auth::guard('admin')->user() ? Auth::guard('admin')->user()->account_id : '';
    }
    
    /**
     * get pagination data
     */
    public function getPagination(){
        // Get list
        $data = Log::where('account_id','=', $this->account_id)->where('extn','=', $this->extn)->where('direction','=', config('dashboard_constant.INBOUND'))->orderBy('log_time', 'DESC')->paginate(config('dashboard_constant.PAGINATION_LIMIT')); 
        return $this->paginationDataFormat($data->toArray());
    }

    /**
     *  mark as read
     */
    public function markAsRead($request, $did, $client){
        return DB::table('log_sms')
            ->where('account_id', $this->account_id)
            ->where('client_number', $client)
            ->update(['status' => config('dashboard_constant.READ')]);
        
    }

    /**
     *  get inbox data
     */
    public function getInboxList($request, $from, $to){
        // Get list
        $sms_from = $from;
        $sms_to = $to;

        $data = Log::where('account_id','=', $this->account_id)
            ->where('client_number','=', $sms_to)
            ->orderBy('log_time', 'DESC')
            ->paginate(config('dashboard_constant.PAGINATION_LIMIT'));
        
        $authUser = Session::get('loginUser');
        //$tz_offset = $this->getTimeZoneOffset($authUser['timezone']);
        foreach($data as $key => $value){
            //$value->log_time=date('Y-m-d H:i:s', strtotime($value->log_time)+$tz_offset);
            $value->log_time=$this->convertTime(config('app.timezone'), $authUser['timezone'], $value->log_time);
        }

        return $this->paginationDataFormat($data->toArray());
    }

    /**
     *  get total inbox inbound count
     */
    public function getInboxInboundCount($request, $from, $to){
        
        $sms_from = $from;
        $sms_to = $to;

        $data = Log::selectRaw("direction , count(*) AS Total")
            ->where('account_id','=', $this->account_id)
            ->where('client_number','=', $sms_to)
            ->where('direction','=', 'I')
            ->groupBy('direction')
            ->get()->toArray(); 

        return $data;
    }

    /**
     *  get total inbox outbound count
     */
    public function getInboxOutboundCount($request, $from, $to){
        
        $sms_from = $from;
        $sms_to = $to;

        $data = Log::selectRaw("direction , count(*) AS Total")
            ->where('account_id','=', $this->account_id)
            ->where('client_number','=', $sms_to)
            ->where('direction','=', 'O')
            ->groupBy('direction')
            ->get()->toArray(); 

        return $data;
    }

    /**
     * old get inbox data
     */
    public function __getInboxList($request, $from, $to){
        // Get list
        $sms_from = $from;
        $sms_to = $to;

        $page = $request->query('page', 1);
        $paginate = config('dashboard_constant.PAGINATION_LIMIT');

        $in = DB::table("log_sms_inbound")->select('log_sms_inbound.sms_from', 'log_sms_inbound.sms_to', 'log_sms_inbound.sms_text', 'log_sms_inbound.log_time')
                    ->where("sms_from", "=", $sms_from)
                    ->where("sms_to", "=", $sms_to);
        $out = DB::table("log_sms_outbound")->select('log_sms_outbound.sms_from', 'log_sms_outbound.sms_to', 'log_sms_outbound.sms_text', 'log_sms_outbound.log_time')
                    ->where("sms_from", "=", $sms_to)
                    ->where("sms_to", "=", $sms_from)
                    ->union($in)
                    ->orderBy('log_time')
                    ->get();
                    
        $offSet = ((int)$page * $paginate) - $paginate;
        $itemsForCurrentPage = array_slice($out->toArray(), $offSet, $paginate, true);
        $data = new \Illuminate\Pagination\LengthAwarePaginator($itemsForCurrentPage, count($out), $paginate, $page);

        return $this->paginationDataFormat($data->toArray());
    }
    /**
     * get log inbound unread message summary detail
     * @param string $accountId
     */
	public function getLogUnreadSummary($accountId){
        return $data = DB::table('log_sms')
        ->select(DB::raw('status, COALESCE(SUM(num_parts),0) as total'))
        ->whereRaw("log_time BETWEEN (NOW() - INTERVAL 7 DAY) AND NOW()")
        ->where('account_id', $accountId)
        ->where('direction', 'I')
        ->where('status', 'U')
        ->first();
    }
    /**
     * get log inbound summary detail
     * @param string $accountId
     */
	public function getLogInboundSummary($accountId){
        return $data = DB::table('log_sms')
        ->select(DB::raw('status, COALESCE(SUM(num_parts),0) as total'))
        ->whereRaw("log_time BETWEEN (NOW() - INTERVAL 7 DAY) AND NOW()")
        ->where('account_id', $accountId)        
        ->where('direction', 'I')
        //->where('status', 'U')
        ->first();
    }
    /**
     * get log outbound summary detail
     * @param string $accountId
     */
	public function getLogOutboundSummary($accountId){
        return $data = DB::table('log_sms')
        ->select(DB::raw('status, COALESCE(SUM(num_parts),0) as total'))
        ->whereRaw("log_time BETWEEN (NOW() - INTERVAL 7 DAY) AND NOW()")
        ->where('account_id', $accountId)        
        ->where('direction', 'O')
        ->first();
    }
}
