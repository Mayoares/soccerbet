<?php
// we must never forget to start the session
session_start();

// $params=$_GET["params"];
// $userId=$params["userId"];
$userId=$_GET["userId"];
echo "userInfo: userId=$userId<br>";
if(strlen($userId)==0)
{
	header("Location: ../util/login.php5");
	exit;
}
include_once("../util/dbutil.php5");
$userName=$dbutil->getUserName($userId);
echo "userInfo: userName=$userName<br>"
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