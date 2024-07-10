<?php
//<!-----
#

include 'db_config.php';
include 'db_functions.php';

########################################################

function get($index){
    if(!empty($_GET[$index])){
        $str=$_GET[$index];
        return $str;
    }
    return "";
}

function genCallid($length)
{
        $rand = genRandNum(4);
        $tstmp = strrev(time());
        return $str = $tstmp.$rand;
}

function write_log($log_msg)
{
    $log_filename = "log";
    if (!file_exists($log_filename)) 
    {
        // create directory/folder uploads.
        mkdir($log_filename, 0777, true);
    }
    $log_file_data = $log_filename.'/log_' . date('d-M-Y') . '.log';
    file_put_contents($log_file_data, $log_msg . "\n", FILE_APPEND);
}

function recieve_sms(){
    $sms_from = get('cli');
    $sms_to = get('sms_to');
    $sms_text = get('message');
    $provider = get('provider');	
    
    $sms_text = mysqli_real_escape_string(getDbConn(), $sms_text);
    //$account_id = '100000';

    $callid = genCallid(4);
    $sms_from = ltrim($sms_from, '+');
    $sms_to = ltrim($sms_to, '+');
    $log_time = date('Y-m-d H:i:s');
	$num_parts = 0;$bill = 0;

    $account_id = db_select_one("SELECT account_id FROM did WHERE did = $sms_to");
    if(!empty($account_id)){
	// check balance
	$balance = db_select_one("SELECT (credit_amount - used_amount) AS balance FROM pbx_gtalk.account WHERE account_id='$account_id'");
	if(!empty($balance)){
		if($balance <= 0 ){
			write_log("[".date('Y-m-d H:i:s')."] From: $sms_from , To: $sms_to, Text: $sms_text, Message: Error: Insufficiient Balance.");
			return;
		}
	}
        $rate = db_select_one("SELECT sms_in_rate FROM pbx_gtalk.account WHERE account_id='$account_id'");
		$num_parts = multipart_count($sms_text);
		if($num_parts < 0){
			$num_parts = 0;
			write_log("[".date('Y-m-d H:i:s')."] From: $sms_from , To: $sms_to, Text: $sms_text, Message: Error: Text too long.");
		}
		$bill = $num_parts * $rate;
        if ($bill > 0) {
          db_update("UPDATE pbx_gtalk.account SET used_amount=used_amount+$bill WHERE account_id='$account_id'");
        } else {
           $bill = 0;
        }
        db_update("INSERT INTO log_sms (account_id, callid, did, client_number, sms_text, log_time, status, direction, rate, num_parts, bill) VALUES ('$account_id', '$callid', '$sms_to', "."'$sms_from', '$sms_text', '$log_time', 'U', 'I', '$rate', '$num_parts', '$bill')");
    }else{
        write_log("[".date('Y-m-d H:i:s')."] From: $sms_from , To: $sms_to, Text: $sms_text, Message: Error: Account Id not found.");
    }
}
db_conn();
recieve_sms();

