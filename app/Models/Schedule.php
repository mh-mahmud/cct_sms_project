<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Contact;
use App\Models\Group;
use App\Models\ScheduleContact;
use DB;

class Schedule extends Model
{
    protected $table = "sms_schedule";

    protected $primaryKey = 'id';

    public function contacts() {
        return $this->hasMany('App\Models\ScheduleContact', 'schedule_id', 'id');
    }

    public function getGroupContacts($data){
        $groupId = array();
        foreach ($data->contacts as $key => $value) {
            if(!empty($value->group_id)){
                array_push($groupId, $value->group_id);
            }
        }
        return Group::whereIn('id', $groupId)->get()->toArray();
    }

    public function getContacts($schedule_id){        
        return ScheduleContact::where('schedule_id', $schedule_id)->where('group_id', '')->get()->toArray();
    }

    public function getContactsByGroupId($schedule_id){        
        return $users = DB::select( DB::raw("SELECT *, concat(name,'(',num_contacts,')') AS name FROM sms_contact_group WHERE id IN ( SELECT group_id FROM sms_schedule_contact WHERE schedule_id='$schedule_id' AND phone='')"));
    }
    
}
