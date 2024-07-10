<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\User;
use App\Models\ScheduleContact;
use Illuminate\Support\Facades\Hash;
use DB;
use Validator;

class ScheduleContactsService extends AppService
{

    public function __construct()
    {
        //
    }
    /**
     * delete contacts from sms_schedule_contact by Group Id
     * @param char $scheduleId
     */
    public function deleteContactsByScheduleId($scheduleId){
        return ScheduleContact::where('schedule_id', $scheduleId)->delete();
    }

}
