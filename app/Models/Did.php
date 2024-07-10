<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Did extends Model
{
    protected $table='did';

    protected $fillable = ['account_id', 'did'];

    public $timestamps = false;
}