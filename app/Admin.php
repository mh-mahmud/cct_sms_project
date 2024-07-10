<?php
namespace App;
/**
 * Remove 'use Illuminate\Database\Eloquent\Model;'
 */
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Admin extends Authenticatable
{
    use Notifiable;
    protected $table = 'account_profile';
    // The authentication guard for admin
    protected $guard = 'admin';    


    function __construct()
   {
      $pbxDbName = config('database.pbx_db_name');
      $this->table = $pbxDbName . '.account_profile';
   }

   /**
    * skip remember token when login/logout
    */
    public function setRememberToken($value)
    {
    }
    /**
     * define primary key
     */
    protected $primaryKey = 'account_id';
     
}