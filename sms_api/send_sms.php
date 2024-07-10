<?php
//<!-----
#

$run_sms_process = 0;                   # Require gPlex SMPP server
$early_mnp_dip = 0;                     # MNP api will be called before processing CTI function.
$diameter_billing = 0;                  # Require diameter middleware server
$insert_cdr_old_tables = 0;             # Insert into old CDR tables
$api_process_count = 10;                # Number of child process for API call
$api_response_timeout = 6;              # soap/curl timeout in sec (range between 4 to 8)

######################################################
global $mysqli;

function db_conn($db_suffix = '') {
global $g, $mysqli;
$db_host = '192.168.10.64';
$db_user = 'sanaul';
$db_pass = 'sanaul123';
$db = 'cc_sms_portal';

########################################################
$mysqli = new mysqli("$db_host","$db_user","$db_pass");
if (!$mysqli) {
  msg("Can't connect to MySQL!");
  return 1;
}

$mysqli->select_db($db);
}

function mysql_keep_alive() {
global $mysqli;
  if($mysqli->ping()!=1 && $mysqli->ping()!=1) {
      @$mysqli->close();
      while(db_conn()==1) sleep(5);
  }
}

function db_update($sql) {
global $mysqli;
   msg($sql);
   $mysqli->query($sql);
   return $mysqli->affected_rows;
}

function db_insert($sql) {
    global $mysqli;
       msg($sql);
       $mysqli->query($sql);
       return $mysqli->affected_rows;
}

function db_select($sql) {
global $mysqli;
   msg($sql);
   @$result = $mysqli->query($sql);
   if($mysqli->affected_rows == 1) {
      $row = $result->fetch_object();
      $mysqli->next_result();
   }
   if(is_object($result)) $result->close();
   if(is_object($row)) return $row;
      else return 0;
}

function db_select_one($sql) {
global $mysqli;
   msg($sql);
   $data = NULL;
   @$result = $mysqli->query($sql);
   if($mysqli->affected_rows == 1) {
      $row = $result->fetch_array(2);
      $data = $row[0];
   }
   if(is_object($result)) $result->close();
   return $data;
}


function db_select_array($sql,$i=0) {
global $mysqli;
   msg($sql);
   @$result = $mysqli->query($sql);
   if($mysqli->affected_rows > 0) {
      while($row = $result->fetch_object()) {                  
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
   if(is_object($result)) $result->close();
   return $obj;
}

function db_select_array_result($sql) {
    global $mysqli;
       msg($sql);
       $rows = array();     
       @$result = $mysqli->query($sql);       
       if($mysqli->affected_rows > 0) {
            while($row = $result->fetch_object()) {
                $rows[]=$row;
            }
       }
       $result->close();
       return $rows; 
}


function msg($str) {
  global $debug,$agi,$logfile;
  if($debug) {
      if(is_array($str) || is_object($str)) $str = print_r($str, true);
  }

  if($debug == 1) echo "$str\n";
    elseif($debug == 2) $agi->verbose($str);
    elseif($debug == 3) file_put_contents($logfile, "$str\n", FILE_APPEND | LOCK_EX);
}

function schedule_process(){
    $row = db_select_array_result("SELECT * FROM sms_schedule JOIN sms_schedule_contact ON sms_schedule.id = sms_schedule_contact.schedule_id WHERE sms_schedule.first_send_time<NOW() AND sms_schedule_contact.status='P' LIMIT 50");    
    if(is_array($row)) {
        foreach($row as $val) {
            //echo $val->id.'<br />';
            if(!empty($val->group_id)){                
                $grow = db_select_array_result("SELECT phone FROM contacts WHERE group_id = $val->group_id");
                foreach($grow as $gval){
                    db_insert("INSERT INTO sms_queue (account_id, user_id, schedule_id, sms_from, sms_to, status, created_at, updated_at, created_by, updated_by) VALUES ($val->account_id, $val->user_id, $val->id, $val->sms_from, $val->phone, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), $val->user_id, $val->user_id");                    
                }
            }else{
                //db_insert("INSERT INTO sms_queue (account_id, user_id, schedule_id, sms_from, sms_to, status, created_at, updated_at, created_by, updated_by) VALUES ($val->account_id, $val->user_id, $val->id, $val->sms_from, $val->phone, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), $val->user_id, $val->user_id");
            }
        }
    }
}
db_conn();
schedule_process();
