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
<link rel="stylesheet" type="text/css" href="../../style/style-current.css" />
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
	?>
	<center>
	<img src="../pics/EM 2016 Tippspiel Logo.PNG" class="image" width="300" alt="Logo_EM_2016">
	</center>
	<?php
	echo "</br>";
	echo "<h1>Hallo $firstname $lastname,</h1>";
	?>
	<p>herzlich Willkommen bei Werke's Tippspiel zur Weltmeisterschaft 2018 in Russland!<p>
	<p>Viel Erfolg beim Tippen w&uuml;nschen Werke, Mayoar und R&oslash;bl!
	<br>
	<br>
	<a href='../pdf/Spielplan_WM_2018_Fifa.pdf'>Download Spielplan (PDF)</a>
	<br>
	<br>
	<?php
	//echo "<p class=\"info\"><font color=\"red\"><u>Hinweis</u>: Die Spielergebnistipps muessen alle <i><u><b>einzeln</b></u></i> abgespeichert werden!</font></p>";
	echo "<p class=\"info\">Hinweis zur Tippabgabe in der Gruppenphase:</br><u><b>Platzierungen</b></u> und <u><b>Gruppenspiele</b></u> m&uuml;ssen <u><b>separat</b></u> gespeichert werden.</p>";
	echo "<p class=\"info\">Hinweis zur Tippabgabe in der Endrunde:</br><u><b>Paarungen</b></u> und <u><b>Ergebnisse</b></u> m&uuml;ssen alle <i><u><b>einzeln</b></u></i> abgespeichert werden!</p>";
	//
	// Kontakt
	//
// 	echo "<br>";
	echo "<h2>Kontakt</h2>";
	//
	echo "<h3>Organisator</h3>";
	echo "<table> <colgroup> <col width='150'> <col width='250'></colgroup>";
	echo "<tr>";
	echo "<td>Mail an Werke:</td><td><a href='mailto:atomkraftwerke@gmx.de' target='_top'>atomkraftwerke@gmx.de</a></td>";
	echo "</tr>";
// 	echo "<tr>";
// 	echo "<td>Richard Mooshammer</td><td>???</td>";
// 	echo "</tr>";
	echo "</table>";
	//
	echo "<br>";
	echo "<h3>Administrator</h3>";
	echo "<table> <colgroup> <col width='150'> <col width='250'></colgroup>";
	echo "<tr>";
	echo "<td>Mail and R&oslash;bl:</td><td><a href='mailto:robert.werkestippspiel@gmail.com' target='_top'>robert.werkestippspiel@gmail.com</a></td>";
	echo "</tr>";
	//echo "<tr>";
	//echo "<td>Andreas Grotemeyer</td><td><a href='mailto:robert.werkestippspiel@gmail.com' target='_top'>mayoar.werkestippspiel@gmail.com</a></td>";
	//echo "</tr>";
	echo "</table>";
}
?>