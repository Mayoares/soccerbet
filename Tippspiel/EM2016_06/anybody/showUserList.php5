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
echo "<link rel='stylesheet' type='text/css' href='../../style/style.css' />";
echo "<br>";
echo "<a href='../admin/overviewAdmin.php5?userId=$userID.php5'>zur&uuml;ck</a>";
echo "<title>Werke's Tippspiel - Teilnehmer</title>";
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