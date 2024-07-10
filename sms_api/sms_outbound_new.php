<?php

include 'db_config.php';
include 'db_functions.php';

$debug = 1;

$logfile = "/usr/local/apache2/htdocs/sms_api/log/error_sms_srv.log";

$sms_rates = array();

$kaka = date_default_timezone_set('Asia/Dhaka');

sms_sending_process();

function sms_sending_process() {
    //global $g, $sms_gw_ip;
    $sms_gw_ip = 'localhost';
    msg("Running SMS sending process");
    db_conn();
    //$cli = '1209';
    $url = 'http://' . $sms_gw_ip . ':3025/cgi-bin/sendsms';
    $user = 'gTalkSMS';
    $pass = 'gTalkSMPP';
    while(1) {
      $sms_messages = getQueueData();
	    //dd($sms_messages);
      if(!empty($sms_messages)){
        dd("Go");
         send_sms($sms_messages, $url, $user, $pass);
      }else{
        dd("No");
         schedule_process();
         sleep(3);
      }
    }
}

function getQueueData(){
   return db_select_array("SELECT * FROM sms_queue LIMIT 50", 1);
}

function send_sms($sms_messages, $url, $user, $pass){
	
   global $sms_rates;
   global $previous_number;
   $timeout_value = 50;
   $sms_numbers = array();

   if (is_array($sms_messages) && !empty($sms_messages)) {
      foreach ($sms_messages as $sms) {
         $bill = 0;$rate = 0;$num_parts = 0;
         $sendStatus = 'F';


         //msg("$sms->sms_from => $sms->sms_to : $sms->sms_text\n");
         if (!empty($sms->sms_to) && ctype_digit($sms->sms_to) && !empty($sms->sms_from) && !empty($sms->sms_text)) {
    			 if($sms->sms_from==$previous_number){
    				 $timeout_value = 1000;
    				 //echo "PREVIOUS NUMBER";
    			 }else{
    				 $timeout_value = 50;
    			 }

			      $previous_number = $sms->sms_from;

            

            if(isset($sms_numbers[$sms->sms_to])){
                sleep(10);
            }else{
                $sms_numbers[$sms->sms_to] = $sms->sms_to;
            }

            if (1==1) {


              /***************************
                  send sms here
              ***************************/
              $result = send_sms_via_MTBAPI($sms->sms_to, $sms->sms_text);
              dd($result);
              $result = @curl_exec($ch);
            if ($result === false) {
               $error_str = "CURL Error: " . curl_error($ch);

            //  error_logs('API', $error_str);
            }else{
               $sendStatus = 'S';
               
            }
            curl_close($ch);
            }
            //echo "\n" . $postdata . "\n";
            usleep($timeout_value);
         }
         else {
         //  error_logs('API', "Failed to send SMS. Number: $sms->sms_to;");
         }
         //$callId = time() . rand(10000, 99999);
         //$num_parts = ceil(strlen($sms->sms_text)/160);
         //$bill = $num_parts * $rate;
         if($num_parts < 0){
             $num_parts = 0;
         }
         $bill = $num_parts * $rate;
         if ($bill > 0) {
            db_update("UPDATE pbx_gtalk.account SET used_amount=used_amount+$bill WHERE account_id='$sms->account_id'");
         } else {
            $bill = 0;
         }


        if ($num_parts > 0) {
          db_update("UPDATE log_sms SET delivery_time=NOW(), account_id='$sms->account_id', callid='$sms->schedule_id', schedule_id='$sms->schedule_id', did='$sms->sms_from', client_number='$sms->sms_to', sms_text='$sms->sms_text', status='D', ob_status='$sendStatus', direction='O', bill='$bill', num_parts='$num_parts', rate='$rate' WHERE id='$sms->id' ");
          db_update("DELETE FROM sms_queue WHERE id='$sms->id' LIMIT 1");
        }

		 echo "#############################  MAKE LOG  ###################################";		 		
		 makeLog($sms->sms_from, $sms->sms_to);
		 
      }
	  
      sleep(10);
   } else {
      sleep(5);
   }
}

function schedule_process() {
    $now = date('Y-m-d H:i:s');
    $schedule = array();
    $scheduleList = db_select_array("SELECT ss.*, ssc.phone, ssc.group_id, ssc.id as ssc_id FROM sms_schedule as ss LEFT JOIN sms_schedule_contact as ssc ".
    "ON ss.id = ssc.schedule_id WHERE start_time < '$now' AND ss.status='P' AND ssc.status='P' LIMIT 10", 1);
    if(!empty($scheduleList)){
      foreach($scheduleList as $item){
        echo "####################################################";
         if (!isset($schedule[$item->id])) $schedule[$item->id] = new SMS_Schedule($item->num_contacts, $item->num_sms_sent);
         $id = genPrimaryKey();
         if(!empty($item->phone)){
            $id = substr($id, -4) . rand(100000, 999999);
            $schedule[$item->id]->processed++;
            db_update("INSERT INTO sms_queue SET id='$id', user_id='123', created_by='123', updated_by='123', account_id='$item->account_id', schedule_id='$item->id', sms_from='$item->sms_from', sms_to='$item->phone', sms_text='$item->sms_text', status='P', created_at=NOW()");

        echo db_update("INSERT INTO log_sms SET id='$id', log_time=NOW(), account_id='$item->account_id', callid='$item->id', schedule_id='$item->id', did='$item->sms_from', client_number='$item->phone', sms_text='$item->sms_text', status='P', ob_status='F', direction='O', bill='0.0', num_parts='0.0', rate='0.0'");

         }
         else if(!empty($item->group_id)){
            //$groupContactList = db_select_array("SELECT id,phone FROM contacts WHERE group_id='$item->group_id' and account_id='$item->account_id' LIMIT 1000", 1);
            $groupContactList = db_select_array("SELECT id,phone FROM contacts, contact_group WHERE contact_group.account_id='$item->account_id' AND contact_group.group_id='$item->group_id' AND contacts.id = contact_group.contact_id LIMIT 1000", 1);
            if(!empty($groupContactList)){
               foreach($groupContactList as $val){
                  $id = substr($id, -4) . rand(100000, 999999);
                  $schedule[$item->id]->processed++;
                  db_update("INSERT INTO sms_queue SET id='$id', user_id='123', created_by='123', updated_by='123', account_id='$item->account_id', schedule_id='$item->id', sms_from='$item->sms_from', sms_to='$val->phone', sms_text='$item->sms_text', status='P', created_at=NOW()");
          
          db_update("INSERT INTO log_sms SET id='$id', log_time=NOW(), account_id='$item->account_id', callid='$item->id', schedule_id='$item->id', did='$item->sms_from', client_number='$item->phone', sms_text='$item->sms_text', status='P', ob_status='F', direction='O', bill='0.0', num_parts='0.0', rate='0.0'");
                  //db_update("UPDATE contacts SET status = 'D' WHERE id='$val->id'");
               }
            }
         }
      }

      foreach($schedule as $key => $obj){
         db_update("UPDATE sms_schedule SET num_sms_sent = num_sms_sent + $obj->processed WHERE id='$key'");
         msg("SMS Schedule: $key, Sent: $obj->num_sms_sent, Num: $obj->num_contacts, Processed: $obj->processed");
         if (($obj->num_sms_sent + $obj->processed) >= $obj->num_contacts) {
             $del_schedule = db_update("DELETE FROM sms_schedule WHERE id='$key' AND num_sms_sent >= num_contacts");
             msg("================DEL: $del_schedule ==============");
             if ($del_schedule) {
                db_update("DELETE FROM sms_schedule_contact WHERE schedule_id='$key'");
             }
         }
      }

    }

}

class SMS_Schedule {
  var $num_sms_sent = 0;
  var $num_contacts = 0;
  var $processed = 0;
  function __construct($contacts, $sent)
  {
     $this->num_sms_sent = $sent;
     $this->num_contacts = $contacts;
  }
}


/********************************
        New Functions
*******************************/
function send_sms_via_MTBAPI($mobileNo, $smsContent) {

  $option=array('trace' => 1);
  $soapClient = new SoapClient("http://192.168.56.54:9090/axis2/services/MTBThirdPartyWebService?wsdl", $option);

  $params = new StdClass();
  $params->mobileNo    = $mobileNo;
  $params->smsContent  = $smsContent;
  $params->userName    = "vivr";
  $params->password    = "vivrtest";


  $functionName = 'sendSMS';
  $arrayName = 'sendSMSRequest';
  // print_r($params);
  try{

    $soap_response = $soapClient->$functionName(array($arrayName => $params));
    
    $response = getXML2Array($soap_response);
    // print_r($response);
    $stoptime  = microtime(true);
    $status = ($stoptime - $starttime) * 1000;
    $status = floor($status);
    // echo "<br/>". "milisecond :".$status;
    echo PHP_EOL. "milisecond :".$status.PHP_EOL;
  }
  catch(Exception $e) {
    echo 'Message: ' .$e->getMessage();
  }

}

function getXML2Array($data){
    $json = json_encode($data);
    return json_decode($json,TRUE);
}