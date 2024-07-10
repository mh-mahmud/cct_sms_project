#!/usr/local/bin/php -q
<?php

include 'db_config.php';
include 'db_functions.php';

$debug = 1;

$logfile = "/usr/local/apache2/htdocs/sms_api/log/error_sms_srv.log";

$sms_rates = array();

date_default_timezone_set('UTC');

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
      if(!empty($sms_messages)){
         send_sms($sms_messages, $url, $user, $pass);
      }else{
         schedule_process();
         sleep(3);
      }
    }
}

function getQueueData(){
   return db_select_array("SELECT * FROM sms_queue GROUP BY sms_from LIMIT 50", 1);
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
				 echo "PREVIOUS NUMBER";
				 makeLog($sms->sms_from, $sms->sms_to);
			 }else{
				 $timeout_value = 50;
			 }
			 echo $sms->sms_from;
			 $previous_number = $sms->sms_from;
            //msg("Text received...");
            $sms->sms_to = '1' . substr($sms->sms_to, -10);
            if(isset($sms_numbers[$sms->sms_to])){
                sleep(10);
            }else{
                $sms_numbers[$sms->sms_to] = $sms->sms_to;
            }
            //$sms->sms_text = base64_decode($sms->sms_text);
            $num_parts = multipart_count($sms->sms_text);
            //msg("Part count: " . $num_parts);
            if ($num_parts > 0) {
            //msg("I am here 1");
            $sms_charset = '&coding=2&charset=utf-8';
            if (mb_detect_encoding($sms->sms_text) == 'UTF-8') {
               //msg("I am here 2");
               $sms->sms_text = iconv('utf-8', 'ucs-2', $sms->sms_text);
			   
			   // replacing quotes
			   $sms->sms_text = str_replace('"', "", $sms->sms_text);
			   $sms->sms_text = str_replace("'", "", $sms->sms_text);
			   
               $sms_charset = '&coding=2&charset=UCS-2';
            }
            //msg("Text: " . $sms->sms_text);
            $postdata = "username=$user&password=$pass&from=".urlencode($sms->sms_from)."&to=$sms->sms_to&text=" . urlencode($sms->sms_text) . $sms_charset;
            $gwurl = $url . '?' . $postdata;
            //msg("SMS=> $gwurl");
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_URL, $gwurl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = @curl_exec($ch);
            if ($result === false) {
               $error_str = "CURL Error: " . curl_error($ch);

            //  error_logs('API', $error_str);
            }else{
               $sendStatus = 'S';
               if (isset($sms_rates[$sms->account_id])) {
                  $rate = $sms_rates[$sms->account_id];
               } else {
                  $sms_rates[$sms->account_id] = db_select_one("SELECT sms_out_rate FROM pbx_gtalk.account WHERE account_id='$sms->account_id'");
                  $rate = $sms_rates[$sms->account_id];
               }
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
         if ($num_parts > 0) db_update("INSERT INTO log_sms SET log_time=NOW(), account_id='$sms->account_id', callid='$sms->schedule_id', schedule_id='$sms->schedule_id', did='$sms->sms_from', client_number='$sms->sms_to', sms_text='$sms->sms_text', status='R', ob_status='$sendStatus', direction='O', bill='$bill', num_parts='$num_parts', rate='$rate'");
         db_update("DELETE FROM sms_queue WHERE id='$sms->id' LIMIT 1");
		 //echo "#############################  MAKE LOG  ###################################";		 		
		 //makeLog($sms->sms_from, $sms->sms_to);
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
         if (!isset($schedule[$item->id])) $schedule[$item->id] = new SMS_Schedule($item->num_contacts, $item->num_sms_sent);
         $id = genPrimaryKey();
         if(!empty($item->phone)){
            $id = substr($id, -4) . rand(100000, 999999);
            $schedule[$item->id]->processed++;
            db_update("INSERT INTO sms_queue SET id='$id', account_id='$item->account_id', schedule_id='$item->id', sms_from='$item->sms_from', sms_to='$item->phone', sms_text='$item->sms_text', status='P', created_at=NOW()");
         }
         else if(!empty($item->group_id)){
            //$groupContactList = db_select_array("SELECT id,phone FROM contacts WHERE group_id='$item->group_id' and account_id='$item->account_id' LIMIT 1000", 1);
            $groupContactList = db_select_array("SELECT id,phone FROM contacts, contact_group WHERE contact_group.account_id='$item->account_id' AND contact_group.group_id='$item->group_id' AND contacts.id = contact_group.contact_id LIMIT 1000", 1);
            if(!empty($groupContactList)){
               foreach($groupContactList as $val){
                  $id = substr($id, -4) . rand(100000, 999999);
                  $schedule[$item->id]->processed++;
                  db_update("INSERT INTO sms_queue SET id='$id', account_id='$item->account_id', schedule_id='$item->id', sms_from='$item->sms_from', sms_to='$val->phone', sms_text='$item->sms_text', status='P', created_at=NOW()");
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
