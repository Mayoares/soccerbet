<?php
// Verbindung zur Datenbank aufbauen
include_once("../../connection/dbaccess.php5");
include_once("../util/calc.php5");
include_once("../../general/log/log.php5");
include_once("../util/dbschema.php5");

$adminuserId=$_GET["userId"];
if(isset($_POST["Cancel"]))
{
	$target="../admin/overviewAdmin.php5?userId=$adminuserId";
	header("Location:$target");
	exit;
}
else if(strlen($adminuserId)==0)
{
	$log=new adminlogger();
	$log->warn("AdminuserId was empty");
	header("Location: ../util/login.php5");
	exit;
}
else
{
	if(!isAdmin($adminuserId))
	{
		header("Location: ../util/login.php5");
		exit;
	}
	else
	{
		run($adminuserId);
	}
}

function isAdmin($adminuserId){

	include_once("../util/dbutil.php5");
	$adminuserName=$dbutil->getUserName($adminuserId);
	if($adminuserName==="admin")
	{
		return true;
	}
	else
	{
		$log=new adminlogger();
		$log->warn("Wrong user (" . $adminuserName . ") tried to add user.");
		return false;
	}
}

function run($adminuserId){

	$log=new adminlogger();
	$log->info("run(".$adminuserId.")");

	printDescription();
	printUserList();
}
	
function printDescription(){
	
	?>
	<!DOCTYPE html>
<html>

<head>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<!-- Mobile viewport -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">

<!-- Facivon 
<link rel="shortcut icon" href="images/favicon.ico"  type="image/x-icon"> -->

<link rel='stylesheet' type='text/css' href='../../style/style-EM2016.css' />

<title>Werke's Tippspiel - User anlegen</title>

</head>
	
<body>

<center>

<img src="../pics/EM 2016 Tippspiel Logo.PNG" class="image" width="300" alt="Logo_EM_2016">

<div class="block">
	<?php 
	echo "<p><a href='../admin/overviewAdmin.php5?userId=$adminuserId'> <h2>zur&uuml;ck</h2> </a> </p>";
	?>
</div>
	
	<?php
	echo "<br>";
	echo "<br>";
	echo "<b><h1>Teilnehmer</h1></b>";
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