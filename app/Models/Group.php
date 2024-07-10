<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table='sms_contact_group';

    public function contacts() {
        return $this->hasMany('App\Models\Contact', 'group_id', 'id');
    }
}