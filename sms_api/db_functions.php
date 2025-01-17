<?php
//<!-----
#DB Lib

$impAccountId = '276ab3cc-a37b-425e-9a56-14a3e64432f6';

$debug = 1;

$dbconn = null;

// SMS TEXT CONFIG
$one_part_limit = 160;
$multi_limit = 153;
$max_parts = 3;
// SMS TEXT CONFIG

function getDbConn() {
    global $g, $db_host, $db_user, $db_pass, $db, $dbconn;
    
    if (!empty($dbconn)) return $dbconn;
    
    $dbconn = mysqli_connect($db_host,$db_user,$db_pass,$db);
    if (!$dbconn) {
      msg("Can't connect to MySQL!");
      return false;
    }
    return $dbconn;
}

function db_conn() {
   global $g, $db_host, $db_user, $db_pass, $db, $dbconn;
   str_replace("search", replace, subject)

   if (!empty($dbconn)) return $dbconn;
   
   $dbconn = mysqli_connect($db_host,$db_user, $db_pass);
   if (!$dbconn) {
   msg("Can not connect to MySQL!");
   return 1;
   }
   mysqli_select_db($dbconn,$db) ;
   return $dbconn; 
}

function escape_string($str) {
   return mysqli_real_escape_string(getDbConn(),$str);
}

function db_update($sql) { 
   global $dbconn;
   msg($sql);
   mysqli_query($dbconn, $sql); 
   return mysqli_affected_rows($dbconn);
}

function db_select($sql) {
   msg($sql);
   $result = mysqli_query(getDbConn(),$sql); 
   $num_rows = mysqli_num_rows($result); 
   if($num_rows == 1) {
      $row = mysqli_fetch_object($result);
   }
   mysqli_fetch_array($result);
   if( isset($row) && is_object($row)) return $row;
      else return 0;
}

function db_select_one($sql) { 
   msg($sql);
   $data = NULL;
   $result = mysqli_query(getDbConn(),$sql); 
   $num_rows = mysqli_num_rows($result); 
   if($num_rows == 1) {
      $row = mysqli_fetch_array($result,2);
      $data = $row[0];
   }
   mysqli_fetch_array($result);
   return $data;
}


function db_select_array($sql,$i=0) { 
   msg($sql);
   $result = mysqli_query(getDbConn(),$sql); 
   $num_rows = mysqli_num_rows($result); 
   if($num_rows > 0) {
      while($row = mysqli_fetch_object($result)) {
        if($i == 0) {
            $key = current($row);   # first row should be unique
            $obj[$key] = $row;
        } else {
            if($i == 1) {
                $obj[] = $row;
            } else {
                # i = 2
                if(!$field) $field = key($row);
                $obj[] = $row->$field;
            }
        }
      }
   } else $obj = 0;
   @mysqli_fetch_array($result);
   return $obj;
}

#function obj($row) {
#      foreach($row as $key => $value)
#         $obj->{$key} = $value;
# return $obj;
#}


function msg($str) {
  global $debug,$agi,$logfile;
  if($debug) {
      if(is_array($str) || is_object($str)) $str = print_r($str, true);
  }

  if($debug == 1) echo "$str\n";
    elseif($debug == 2) $agi->verbose($str);
    elseif($debug == 3) file_put_contents($logfile, "$str\n", FILE_APPEND | LOCK_EX);
}

function count_gsm_string($str)
{
    @$gsm_7bit_basic = "@£$¥èéùìòÇ\nØø\rÅåΔ_ΦΓΛΩΠΨΣΘΞÆæßÉ !\"#¤%&'()*+,-./0123456789:;<=>?¡ABCDEFGHIJKLMNOPQRSTUVWXYZÄÖÑÜ§¿abcdefghijklmnopqrstuvwxyzäöñüà";
    $gsm_7bit_extended = "^{}\\[~]|€";
    $len = 0;
    for($i = 0; $i < mb_strlen($str); $i++) {
        if(mb_strpos($gsm_7bit_basic, $str[$i]) !== FALSE) {
            $len++;
        } else if(mb_strpos($gsm_7bit_extended, $str[$i]) !== FALSE) {
            $len += 2;
        } else {
            return -1; // cannot be encoded as GSM, immediately return -1
        }
    }
    return $len;
}

function count_ucs2_string($str)
{
    $utf16str = mb_convert_encoding($str, 'UTF-16', 'UTF-8');
    $byteArray = unpack('C*', $utf16str);
    return count($byteArray) / 2;
}

function multipart_count($str)
{
    $str_length = count_gsm_string($str);
    if($str_length === -1) {
        $one_part_limit = 70; // ... constant
        $multi_limit = 67; // ... constant
        $str_length = count_ucs2_string($str);
    }
    if($str_length <= $one_part_limit) {
        // fits in one part
        return 1;
    } else if($str_length > ($max_parts * $multi_limit)) {
        // too long
        return -1; // or throw exception, or false, etc.
    } else {
        // divide the string length by multi_limit and round up to get number of parts
        return ceil($str_length / $multi_limit);
    }	
}

function dd($data){
   echo "<pre>";
   print_r($data);
   echo "</pre>";
   die();
}
function dump($data){
   echo "<pre>";
   var_dump($data);
   echo "</pre>";
}
/**
 * generate rand number max (9 char)
*/
function genRandNum($length){
   $digits = $length;
   return $rand = rand(pow(10, $digits-1), pow(10, $digits)-1);
}
function genPrimaryKey()
{
   $rand = genRandNum(4);
   $tstmp = strrev(time());
   return $str = $tstmp.$rand;
}

function makeLog($sms_from='', $sms_to='', $msg='')
{
	$logs = opendir('log');

	while (($log = readdir($logs)) !== false)
	{
		if ($log == '.' || $log == '..')
			continue;

		if (filectime('log/'.$log) <= time() - 14 * 24 * 60 * 60)
		{
			unlink('log/'.$log);
		}
	}

	closedir($logs);
	if($sms_from!='' && $sms_to!=''){
		$log  = "sms_from: ".$sms_from.PHP_EOL.
        "sms_to: ".$sms_to.PHP_EOL.
		 "time: ".date("Y-m-d H:i:s").PHP_EOL.
		 "Message : ".$msg.PHP_EOL.
        "-------------------------".PHP_EOL;
	}else{
		$log  = "Message : ".$msg.PHP_EOL.
        "-------------------------".PHP_EOL;
	}
	file_put_contents('./log/log_'.date("j.n.Y").'.log', $log, FILE_APPEND);d;
}


//------>
