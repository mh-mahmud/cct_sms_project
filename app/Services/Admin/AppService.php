<?php

namespace App\Services\Admin;

use Illuminate\Http\Request;
use DB;
use Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;

class AppService
{
    
    /**
     * send email
     * @paran string $to
     * @paran string $from
     * @paran string $subject
     * @paran string $emailData
     */
    public function sendEmail($to, $from, $fromName, $subject, $emailData, $cc = [], $bcc = []){ 
        Mail::to($to)->send(new SendMailable($from, $fromName, $subject, $emailData, $cc, $bcc));
        if (count(Mail::failures()) > 0) {
            return [
                config('msg_label.MSG_RESULT') => false,
                config('msg_label.MSG_MESSAGE') => "Email send failed"
            ];
        }
        return [
            config('msg_label.MSG_RESULT') => true,
            config('msg_label.MSG_MESSAGE') => "Email send successfully"
        ];
    }
    public function paginationDataFormat($data){        
        $pagination = [];
        $pagination['data'] = $data['data'];

        // pagination meta value
        $pagination['meta']['current_page'] = $data['current_page'];
        $pagination['meta']['from'] = $data['from'];
        $pagination['meta']['last_page'] = $data['last_page'];
        $pagination['meta']['path'] = $data['path'];
        $pagination['meta']['per_page'] = $data['per_page'];
        $pagination['meta']['to'] = $data['to'];
        $pagination['meta']['total'] = $data['total'];

        // pagination links
        $pagination['links']['first'] = $data['path'].'?page=1';
        $pagination['links']['last'] = $data['path'].'?page='.$data['last_page'];
        $pagination['links']['next'] = $data['next_page_url'];
        $pagination['links']['prev'] = ($data['current_page'] <= 1 ) ? null : $data['path'].'?page='.($data['current_page']-1);
        // $pagination['allData'] = $data;

        return $pagination;
    }
    public function batchUpdate($table, $values, $index)
    {
        $final  = array();
        $ids    = array();
        if(!count($values))
            return false;
        if(!isset($index) AND empty($index))
            return false;
        foreach ($values as $key => $val)
        {
            $ids[] = $val[$index];
            foreach (array_keys($val) as $field)
            {
                if ($field !== $index)
                {
                    $value           = (is_null($val[$field]) ? 'NULL' : '"' . $val[$field] . '"');
                    $final[$field][] = 'WHEN `'. $index .'` = "' . $val[$index] . '" THEN ' . $value . ' ';
                }
            }
        }
        $cases = '';
        foreach ($final as $k => $v)
        {
            $cases .=  '`'. $k.'` = (CASE '. implode("\n", $v) . "\n"
                            . 'ELSE `'.$k.'` END), ';
        }
        $query = "UPDATE `$table` SET " . substr($cases, 0, -2) . " WHERE `$index` IN(" . '"' . implode('","', $ids) . '"' . ");";
        return DB::statement($query);
    }
    /**
     * get user time zone offset
     */
    public function getTimeZoneOffset($timezone){
        $tz_offset = 0;
        if (!empty($timezone)) {
            $tzObj = new \DateTimeZone($timezone);
            $time_in_local = new \DateTime('now', $tzObj);
            $tz_offset =  $tzObj->getOffset( $time_in_local );
        }
        return $tz_offset;
    }
    /**
     * convert timeZone
     */
    public function convertTime($db_zone, $client_zone, $time){
        $db_time = \DateTime::createFromFormat('Y-m-d H:i:s', $time, new \DateTimeZone($db_zone));
        $date = $db_time->setTimeZone(new \DateTimeZone($client_zone));
        return $date->format('Y-m-d H:i:s');
    }
    /**
     * generate rand number max (9 char)
     */
    public function genRandNum($length){
        $digits = $length;
        return $rand = rand(pow(10, $digits-1), pow(10, $digits)-1);
    }
    /**
     * generate date to timestamp
     * @param str $date (date must be Y-m-d H:i:s format)
     */
    public function dateToTimestamp($date)
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->toDateTimeString();
    }
    /**
     * generate date to timestamp
     * @param str $date (date must be Y-m-d H:i:s format)
     */
    public function getGMTTime($schedule_date, $time_zone)
    {
        $default_time_zone = date_default_timezone_get();
        date_default_timezone_set($time_zone);
        $schedule_date = $this->dateToTimestamp($schedule_date);
        $datetime = new \DateTime($schedule_date);

        date_default_timezone_set($default_time_zone);
        $de_time = new \DateTimeZone($default_time_zone);        
        $datetime->setTimezone($de_time);
        
        return $datetime->format('Y-m-d H:i:s');
    }
    /**
     * generate primary key (10 char)
     */
    public function genPrimaryKey()
    {
        $rand = $this->genRandNum(4);
        $tstmp = substr(time(),4); 
        return $str = $tstmp.$rand;
    }
    /**
     * GET ACCOUNT ID FORM SESSION
     */
    public function getAccountId(){
        $value = Auth::user()->account_id;
        return $value;
    }
    /**
     * process response for service
     * @param boolean $result (true | false)
     * @param str $msg 
     * @param array|object|str $responseData (response data that need to return)
     */
    public function processServiceResponse($result, $msg, $responseData){
        return [
            config('msg_label.MSG_RESULT') => $result,
            config('msg_label.MSG_MESSAGE') => $msg,
            config('msg_label.MSG_DATA') => $responseData
        ];
    }
    /**
     * process response for controller
     * @param boolean $result (true | false)
     * @param str $msg 
     */
    public function processControllerResponse($result, $msg){
        return [
            config('msg_label.RESPONSE_MSG') => [
                config('msg_label.MSG_TYPE') => $result == true ? config('msg_label.MSG_SUCCESS') : config('msg_label.MSG_ERROR'),
                config('msg_label.MSG_TITLE') => $result == true ? config('msg_label.MSG_SUCCESS_TITLE') : config('msg_label.MSG_ERROR_TITLE'), 
                config('msg_label.MSG_MESSAGE') => $msg
            ]
        ];
    }
    /**
     * process response for controller with data
     * @param boolean $result (true | false)
     * @param str $msg
     * @param array|object|str $responseData (response data that need to return)
     */
    public function processControllerResponseWithData($result, $msg, $responseData){
        return [
            config('msg_label.RESPONSE_MSG') => [
                config('msg_label.MSG_TYPE') => $result == true ? config('msg_label.MSG_SUCCESS') : config('msg_label.MSG_ERROR'),
                config('msg_label.MSG_TITLE') => $result == true ? config('msg_label.MSG_SUCCESS_TITLE') : config('msg_label.MSG_ERROR_TITLE'), 
                config('msg_label.MSG_MESSAGE') => $msg,
                config('msg_label.MSG_DATA') => $responseData
            ]
        ];
    }
}
