<?php
	
	include 'class.php';
	include 'db_config.php';
	include 'db_functions.php';

	$prefix="NPABX_";
	$admin_prefix="NPABXAD_";
	$logInUrl = 'https://www.gtalkpbx.com/sms?a=1';
	//$logInUrl = 'http://192.168.10.81/ccsms/login?a=1';
	
	session_start();
		
	
	function get($index){
		if(!empty($_GET[$index])){
			$str=$_GET[$index];
			return $str;
		}
		return "";
	}
	
	function CheckUserSessionData() {
		global $prefix, $admin_prefix;
		
		if (isset ( $_SESSION [$admin_prefix.'loggedAdminData'] )) {
				$data = unserialize ( $_SESSION [$admin_prefix.'loggedAdminData'] );
				if (isset($data->LoggedIn)&& $data->LoggedIn) {
				if(isset($data->account_id) && $data->account_id && isset($data->extn) && $data->extn){
					if($data->account_id==get('account_id') && $data->extn==get('extn')){
						return true;
					}
				}
			}
		}	
		if (isset ( $_SESSION [$prefix.'loggedCallCenterData'] )) {
			$data = unserialize ( $_SESSION [$prefix.'loggedCallCenterData'] );
			if (isset($data->LoggedIn)&& $data->LoggedIn) {
				if(isset($data->account_id) && $data->account_id && isset($data->extn) && $data->extn){
					if($data->account_id==get('account_id') && $data->extn==get('extn')){
						return true;
					}
				}
			}
		} 	
		if (isset ( $_SESSION [$prefix.'loggedUserData'] )) {
			$data = unserialize ( $_SESSION [$prefix.'loggedUserData'] );
			if (isset($data->LoggedIn)&& $data->LoggedIn) {
				if(isset($data->account_id) && $data->account_id && isset($data->extn) && $data->extn){
					if($data->account_id==get('account_id') && $data->extn==get('extn')){
						return true;
					}
				}
			}
		} 
		
		if (isset ( $_SESSION [$prefix.'loggedExtnUserData'] )) {
			$data = unserialize ( $_SESSION [$prefix.'loggedExtnUserData'] );		
			if (isset($data->LoggedIn)&& $data->LoggedIn) {
				if(isset($data->account_id) && $data->account_id && isset($data->extn) && $data->extn){
					if($data->account_id==get('account_id') && $data->extn==get('extn')){
						return true;
					}
				}
			}
		} 
			
		return null;
	}
	if(!CheckUserSessionData()){
		echo 'User not Found!';
		die();
	}
	$account_id = get('account_id');
	$extn = get('extn');
	$password = '';
	$user = db_select_array("SELECT user.*, user_profile.* FROM user LEFT JOIN user_profile ON user.userid=user_profile.userid WHERE user.account_id = $account_id AND user.extn = $extn limit 1");	
	if(!empty($user)){		
		foreach($user as $item){
			$password = $item->password;
		}
?>
<html>
<head>
<script>
function load()
{
document.frm1.submit()
}
</script>
</head>
<body onload="load()">
<form action="<?php echo $logInUrl;?>" id="frm1" name="frm1" method="post" style="display: none;">
<input type="text" name="account_id" value="<?php echo $account_id ?>">
<input type="text" name="extn" value="<?php echo $extn ?>">
<input type="text" name="password" value="<?php echo $password ?>">
<input type="submit" value="submit"/>
</form>
</body>
</html> 
<?php		
	}
?>