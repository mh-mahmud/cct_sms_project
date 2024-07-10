<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ComposeService;
use Auth;
use Session;

class ComposeController extends Controller
{

    public $Service;
   
   /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->Service = new ComposeService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $authUser = Session::get('loginUser');

        $layoutData['js_plugin'] = $this->getJsPlugin(["JSP_BOOTSTRAP_SELECT2","JSP_BOOTSTRAP_DATEPICKER", "JSP_BOOTSTRAP_TAGINPUT"]);    
        $layoutData['templates'] = $this->Service->getTemplates();
        $layoutData['groups'] = $this->Service->getGroups();
        $layoutData['did'] = $this->Service->getDid();
        $layoutData['title'] = 'Compose | '.config("app.name");
        $layoutData['user_time_zone'] = $authUser['timezone'];
        $layoutData['time_zone'] = config("time_zone");
        $layoutData['sms_text_lengths_ascii'] = config("dashboard_constant.SMS_TEXT_ASCII");
        $layoutData['sms_text_lengths_unicode'] = config("dashboard_constant.SMS_TEXT_UNICODE");
        $layoutData['sms_text_size'] = config("dashboard_constant.SMS_TEXT_SIZE");
        $layoutData['sms_text_part'] = config("dashboard_constant.SMS_TEXT_PART");
        $layoutData['sms_text_part_size'] = config("dashboard_constant.SMS_TEXT_PART_SIZE");
        return response()->json($layoutData);
    }

    /**
     * Save Compose Data.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveCompose(Request $request)
    {
        $data = $this->Service->save($request); 
        $responseMsg = $this->Service->processControllerResponse($data[config('msg_label.MSG_RESULT')], $data[config('msg_label.MSG_MESSAGE')]);
        return response()->json($responseMsg); 
    }

    /**
     * Save Reply Data.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveReply(Request $request)
    {
        $data = $this->Service->saveReply($request); 
        $responseMsg = $this->Service->processControllerResponse($data[config('msg_label.MSG_RESULT')], $data[config('msg_label.MSG_MESSAGE')]);
        return response()->json($responseMsg); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateCompose(Request $request, $id)
    {
        $data = $this->Service->updateData($request, $id); 
        $responseMsg = $this->Service->processControllerResponse($data[config('msg_label.MSG_RESULT')], $data[config('msg_label.MSG_MESSAGE')]);
        return response()->json($responseMsg); 
    }

    /**
     * get Compose Detail.
     * @param  char  $id
     * @return \Illuminate\Http\Response
     */
    public function getDetail($id)
    {
        $layoutData['js_plugin'] = $this->getJsPlugin(["JSP_BOOTSTRAP_SELECT2","JSP_BOOTSTRAP_DATEPICKER", "JSP_BOOTSTRAP_TAGINPUT"]);
        $layoutData['templates'] = $this->Service->getTemplates();
        $layoutData['groups'] = $this->Service->getGroups();
        $layoutData['did'] = $this->Service->getDid();
        $layoutData['title'] = 'Compose | '.config("app.name");
        $layoutData['time_zone'] = config("time_zone");
        $layoutData['sms_text_lengths_ascii'] = config("dashboard_constant.SMS_TEXT_ASCII");
        $layoutData['sms_text_lengths_unicode'] = config("dashboard_constant.SMS_TEXT_UNICODE");
        $layoutData['sms_text_size'] = config("dashboard_constant.SMS_TEXT_SIZE");
        $layoutData['sms_text_part'] = config("dashboard_constant.SMS_TEXT_PART");
        $layoutData['sms_text_part_size'] = config("dashboard_constant.SMS_TEXT_PART_SIZE");

        $layoutData['data'] = $this->Service->getScheduleDetail($id, false);
        return response()->json($layoutData); 
    }
}
