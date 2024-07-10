<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Services\LogService;
use App\Models\Inbound;
use Session;

class InboxController extends Controller
{

    public $Service;
   
   /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->Service = new LogService();
    }

    public function getInboxList(Request $request, $from, $to){

        // mark as read
        //$this->Service->markAsRead($request, $from, $to);

        $authUser = Session::get('loginUser'); 

        $data = $this->Service->getInboxList($request, $from, $to);
        $data['inbound'] = $this->Service->getInboxInboundCount($request, $from, $to);
        $data['outbound'] = $this->Service->getInboxOutboundCount($request, $from, $to);
        $data['status'] = config("dashboard_constant.LOG_SMS_STATUS");
        $data['timezone'] =  $authUser['timezone'];
        $data['directions'] = config("dashboard_constant.SMS_DIRECTION");
        $data['sms_text_lengths_ascii'] = config("dashboard_constant.SMS_TEXT_ASCII");
        $data['sms_text_lengths_unicode'] = config("dashboard_constant.SMS_TEXT_UNICODE");
        $data['sms_text_size'] = config("dashboard_constant.SMS_TEXT_SIZE");
        $data['sms_text_part'] = config("dashboard_constant.SMS_TEXT_PART");
        $data['sms_text_part_size'] = config("dashboard_constant.SMS_TEXT_PART_SIZE");
        return response()->json($data); 
    }
}
