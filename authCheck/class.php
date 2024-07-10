<?php 
class UserSessionData{
	public $account_id;
	public $name;
	public $email;
	public $plan_code;	
	public $plan_name;
	public $plan_type;
	public $state;
	public $LoggedIn;
}

class ExtensionUserData{
	var $name;
	var $extn;
	var $user_id;
	var $account_id;
	var $pin;
	var $email;
	var $LoggedIn;
	var $plan_type;
	var $account_name;
	var $allow_efax;
	var $efax_folders=array();
	function  __construct(){
		$this->name=&$this->account_name;
	}
}
class CallCenterSessionData{		
	var $userName;
	var $agent_id;
	var $LoggedIn;
}
class AdminSessionData{
	var $user_id;	
	var $title;
	var $LoggedIn;
}
?>