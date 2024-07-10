<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    
    public function group() {
        return $this->belongsTo('App\Models\Group', 'group_id', 'id');
    }
}
