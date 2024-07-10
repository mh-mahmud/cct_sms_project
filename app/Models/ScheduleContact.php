<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleContact extends Model
{
    protected $table = 'sms_schedule_contact';

    public function schedule() {
        return $this->belongsTo('App\Models\Schedule', 'schedule_id', 'id');
    }
}
