<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\User;
use App\Models\UserNew;
use Illuminate\Support\Facades\Hash;
use Validator;
use DB;

class UsersService extends AppService
{
    
    /**
     * get pagination data
    */

    /*public function getPagination(){ 
        // Get list
        $account_id = $this->getAccountId();
        $data = User::where('account_id', $account_id)->orderBy('created_at', 'DESC')->paginate(config('dashboard_constant.PAGINATION_LIMIT')); 
        return $this->paginationDataFormat($data->toArray());
    }*/

    /*public function __construct() {
        $x = $this->getNewAccountId();
        dd($x);
    }*/

    public function getPagination(){ 
        // Get list
        $account_id = $this->getAccountId();
        $data = UserNew::where('username', '!=', 'root')->paginate(config('dashboard_constant.PAGINATION_LIMIT'));
        // $data = DB::select("SELECT * FROM users");
        // dd($data->toArray());
        return $this->paginationDataFormat($data->toArray());
    }
    /**
     * save data
     * @param array request
     */
    public function save($request){

        Validator::make($request->all(),[
            'first_name' => 'required|string|max:30',
            'last_name' => 'required|string|max:30',
            'username' => 'required|string|max:50|unique:users',
            'email' => 'required|string|email|max:60|unique:users',
            'password' => 'required|string|min:6|max:32|confirmed',
        ])->validate();
        
        $userid = $this->genUserId();
        $account_id = $this->getNewAccountId();

        // Create or Update 
        // $dataObj =  new User;
        $dataObj =  new UserNew;
        $dataObj->id = $userid;
        $dataObj->account_id = $account_id;
        $dataObj->first_name = $request->input('first_name');
        $dataObj->last_name = $request->input('last_name');
        $dataObj->username = $request->input('username');
        $dataObj->email = $request->input('email');
        $dataObj->password = Hash::make($request->input('password'));
        $dataObj->status = 'A';
        $dataObj->type = 'AG';

        if($dataObj->save()) {

            // insert data to the pbx_db.user table
            $dataObj_2 =  new User;
            $dataObj_2->userid = $userid;
            $dataObj_2->account_id = $account_id;
            $dataObj_2->fname = $request->input('first_name');
            $dataObj_2->lname = $request->input('last_name');
            $dataObj_2->cname = $request->input('username');
            $dataObj_2->extn = "101";
            $dataObj_2->status = 'A';
            $dataObj_2->user_email = $request->input('email');
            $dataObj_2->user_type = 'AG';
            $dataObj_2->save();

            return $this->processServiceResponse(true, "User Added Successfully!",$dataObj);
        }
        return $this->processServiceResponse(false, "User Added Failed!",$dataObj);
    }
    /**
     * GENERATE RANDOM USER ID
     */
    public function genUserId(){
        $id = $this->genPrimaryKey();
        $usrExists = User::find($id);
        if($usrExists){
            return $this->genUserId();
        }
        return $id;
    }

    public function getNewAccountId() {
        return UserNew::where('status', 'A')->orderBy('created_at', 'DESC')->first()->account_id+1;
    }

    /**
     * get details
     * $param int $id
     */
    public function getDetail($id){
        //Get detail
        return UserNew::findOrFail($id); 

    }

    /**
     * update data
     * @param array request
     */
    public function updateData($request){
        Validator::make($request->all(),[
            'first_name' => 'required|string|max:30',
            'last_name' => 'required|string|max:30',
            'username' => 'required|string|max:50|unique:users,username,'.$request->id,
            'email' => 'required|string|email|max:60|unique:users,email,'.$request->id,
            'password' => 'string|min:6|max:32|confirmed',

        ])->validate();
        
        // get detail
        $dataObj = $this->getDetail($request->id);
        
        $dataObj->first_name = $request->input('first_name');
        $dataObj->last_name = $request->input('last_name');
        $dataObj->username = $request->input('username');
        $dataObj->email = $request->input('email');
        $dataObj->type = $request->input('type');
        $dataObj->status = $request->input('status');
        if(!empty($request->input('password'))){
            $dataObj->password = Hash::make($request->input('password'));
        }

        if($dataObj->save()) { 
            return $this->processServiceResponse(true, "User Update Successfully!",$dataObj);
        }
        return $this->processServiceResponse(false, "User Update Failed!",$dataObj);

    }

    /**
     * delete data
     * @param int $id
     */
    public function delete($id){ 
        $dataObj = $this->getDetail($id); 
        if($dataObj->delete()) {
            return $this->processServiceResponse(true, "User Deleted Successfully!",$dataObj);
        }
        return $this->processServiceResponse(false, "User Deleted Failed!",$dataObj);
    }
    /**
     * get account info by id
     * @param string $accountId
     */
    public function getAccountInfo($accountId){
        $pbxDbName = config('database.pbx_db_name');
        return $data = DB::table($pbxDbName.'.account')
        ->where('account.account_id',$accountId)
        ->leftJoin($pbxDbName.'.account_profile as ap','account.account_id','=','ap.account_id')
        ->first();
    }
    public function passwordReset($request){        
        $pbxDbName = config('database.pbx_db_name');  
        $accountId = \Auth::user() ? \Auth::user()->account_id : '';
        $userId = \Auth::user() ? \Auth::user()->userid : '';        
        $user = DB::table($pbxDbName.'.user_profile')
                        ->where('user_profile.userid',$userId)
                        ->where('user_profile.account_id',$accountId)                        
                        ->first();
        if ($user && $user->password == md5($user->userid.$request->old_password)) {
            $data = DB::table($pbxDbName.'.user_profile')
                        ->where('user_profile.userid',$userId)
                        ->where('user_profile.account_id',$accountId)                        
                        ->where('user_profile.password',md5($user->userid.$request->old_password))
                        ->limit(1)
                        ->update(['password' => md5($user->userid.$request->password)]);                        
            if($data) {
                return $this->processServiceResponse(true, "Password Changed Successfully!",$data);
            }
            return $this->processServiceResponse(false, "Password Changed Failed!",$data);
        }else{
            return $this->processServiceResponse(false, "Old Password Does Not Match!",null);
        }
    }
}
