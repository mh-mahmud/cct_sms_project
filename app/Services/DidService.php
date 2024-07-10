<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\User;
use App\Models\Did;
use Illuminate\Support\Facades\Hash;
use DB;
use Validator;
use Auth;

class DidService extends AppService
{

    public function __construct()
    {        
        $this->account_id = Auth::user()->account_id;
    }
    
    /**
     * save data
     * @param array request
     */
    public function save($request){
        $phone = $request->input('phone');
        $account_id = $this->getAccountId();
        $did = Did::where('account_id', '=', $account_id)
                    ->where('did', '=', $phone)
                    ->first();
        if ($did === null) {
            $dataObj = new Did();
            $dataObj->account_id = $account_id;
            $dataObj->did = $phone;
            $dataObj->save();
        }
    }

    /**
     * update data
     * @param array request
     */
    public function updateData($didUpdatePhone, $dataObj){
        $phone = $didUpdatePhone;
        $account_id = $this->getAccountId();
        return \DB::statement("UPDATE did SET did=$dataObj->phone where did=$phone and account_id=$account_id");
        return \DB::table('did')
                ->where('account_id', $account_id)
                ->where('did', $phone)
                ->update(array('did' => $dataObj->phone));        
    }
	
}
