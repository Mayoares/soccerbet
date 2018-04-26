<?php
session_start();
$userId=$_GET["userId"];
include_once("../../connection/dbaccess.php5");
include_once("../util/dbschema.php5");
if(isset($_POST["Cancel"]))
{
	$target="./overview.php5?userId=$userId";
	header("Location:$target");
	exit;
}
else if(isset($_POST["changePwd"]))
{
	promptChangePassword($userId);
}
else 
{
	echo "<html>";
	echo "<head>";
	echo "<link rel='stylesheet' type='text/css' href='../../style/style-current.css' />";
	echo "<title>Werke's Tippspiel - Passwort &auml;ndern</title>";
	echo "</head>";
	echo "<body>";
	if((!isset($_POST["changePassword"])) or
			(empty($_POST["passwordNew1"])) or 
			(empty($_POST["passwordNew2"])))
	{ 
		if(empty($_POST["passwordNew1"]) or empty($_POST["passwordNew2"]))
		{
			echo "<br><p class=\"info\">Bitte neues Passwort eingeben!</p>";
			promptChangePassword($userId);
		}
		else
		{
			echo "<br>Kein Direktaufruf dieses Skripts! Bitte auf ./user/changePassword.php5 wechseln.";
		}
	}
	else
	{
		if($_POST["passwordNew1"] === $_POST["passwordNew2"])
		{
			changePasswordInDB($userId, $_POST["passwordNew1"]);
		}
		else
		{
			echo "<br><p class=\"info\">Eingegebene neue Passw&ouml;rter waren unterschiedlich! Bitte Eingabe wiederholen!</p>";
			promptChangePassword($userId);
		}
	}
}

function promptChangePassword($userid)
	{
	$password=getPassword($userid);
	echo "<form method='POST' action='changePassword.php5?userId=$userid'>";
	echo "<table>";
	echo "<tr>";
	echo "<td>altes Passwort      	:	</td><td><input type='password' name='passwordOld' value='$password' disabled='true'></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>neues Passwort      	:	</td><td><input type='password' name='passwordNew1' value=''></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>neues Passwort (Wdh.) :	</td><td><input type='password' name='passwordNew2' value=''></td>";
	echo "</tr>";
// 	echo "<tr>";
// 	echo "</tr>";
// 	echo "<tr>";
// 	echo "</tr>";
	echo "<tr>";
	echo "<td><input type='submit' name='changePassword' value='Passwort &auml;ndern'> &nbsp</td><td></td>";
	echo "</tr>";
	}

function changePasswordInDB($userid, $passwordnew)
	{
	include_once("../util/dbutil.php5");
	include_once("../../general/log/log.php5");
	$log=new logger();
	$table_users=dbschema::users;
	$username=$dbutil->getUserName($userid);
	$sqlupdatePassword="UPDATE $table_users SET `password` =  '$passwordnew' WHERE $table_users.`userid` = '$userid'";
	$result=mysql_query($sqlupdatePassword);
	if($result==1)
	{
		$log->info("Passwort ge&auml;ndert f&uuml;r User=$username mit UserId=$userid");
		echo "<br><font color=\"green\">Passwort ge&auml;ndert!</font>";
		echo "<br>";
	}
	else
	{
		promptChangePassword($userid);
	}
	}

function getPassword($userid){
	
	$table_users=dbschema::users;
	$result=mysql_query("SELECT * FROM $table_users WHERE userid = '$userid'");
	$array=mysql_fetch_array($result);
	$password=$array["password"];
	return $password;
}
?>