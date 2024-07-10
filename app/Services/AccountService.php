<?php

namespace App\Services;

use Illuminate\Http\Request;
use DB;

class AccountService extends AppService
{
   /**
    * get account list
    * @param string $key
    * @param string $val
    */
   public function getAccList($key = 'account_id', $val = 'name'){    
        return $data = DB::table('accounts')->pluck($val, $key)->toArray();
   }


}
