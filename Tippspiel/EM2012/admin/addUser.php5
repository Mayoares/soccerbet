<?php
include_once("../../connection/dbaccess.php5");
include_once("../../general/log/log.php5");
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
	if((!isset($_POST["addUser"])) or
	(empty($_POST["username"])) or
	(empty($_POST["firstname"])) or
	(empty($_POST["lastname"])))
	{
		if(empty($_POST["username"]))
		{
			echo "<br>Username fehlt. Bitte Eingabe korrigieren.";
			promptAddUser($adminuserId);
		}
		else if(empty($_POST["firstname"]))
		{
			echo "<br>Vorname fehlt. Bitte Eingabe korrigieren.";
			promptAddUser($adminuserId);
		}
		else if(empty($_POST["lastname"]))
		{
			echo "<br>Nachname fehlt. Bitte Eingabe korrigieren.";
			promptAddUser($adminuserId);
		}
		else
		{
			echo "<br>Kein Direktaufruf dieses Skripts! Bitte auf ./util/login.php5 wechseln.";
		}
	}
	else
	{
		$username=$_POST["username"];
		$firstname=$_POST["firstname"];
		$lastname=$_POST["lastname"];
		$email=$_POST["email"];

		addUser($username, $firstname, $lastname, $email, $adminuserId);
	}
}

function addUser($username, $firstname, $lastname, $email, $adminuserId)
{
	
	$log=new adminlogger();
	$log->info("run addUser(".$username.",". $firstname.",". $lastname.",". $email.")");
	include_once("../util/dbschema.php5");
	$newuserId = createUserId();
	$password = "123";
	$table_users=dbschema::users;

	// insert into DB
	$sqlinsert="INSERT INTO $table_users (    `userid`,  `username`,  `password`,  `lastname`,  `firstname`,  `email`, `finalparticipantscore`) " .
			                     "VALUES ('$newuserId', '$username', '$password', '$lastname', '$firstname', '$email', b'0')";
	$log=new adminlogger();
	$log->info($sqlinsert);
	$sqlinsertResult=mysql_query($sqlinsert);
	if(!$sqlinsertResult)
	{
		echo 'Datenbankfehler. MIST!<br>';
		echo mysql_error();
		echo "<br>";
		echo "<br>";
		echo "<a href='./overviewAdmin.php5?userId=$adminuserId'>zurück zur Übersicht</a>";
	}
	else
	{
		echo '<br>User wurde angelegt:<br>';
		$userInfo = "<br>UserID=$newuserId<br>Username=$username<br>Vorname=$firstname<br>Nachname=$lastname<br>email=$email";
		// TODO instead: send mail with initial password to user
		echo $userInfo."<br>Initiales Passwort=$password";
		echo "<form method=\"POST\" action=\"overviewAdmin.php5?userId=$adminuserId\">";
		echo "<br>";
		echo "<input type=\"submit\" name=\"ok\" value=\"OK\">";
		echo "</form>";
	}
}

function createUserId()
{
	return md5(uniqid());
}

function promptAddUser($adminuserId)
{
	echo "<form method='POST' action='addUser.php5?userId=$adminuserId'>";
	echo "<table>";
	echo "<tr>";
	echo "<td>User-Name:</td><td>	<input type='text' name='username' value=''></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>Vorname :</td><td>	<input type='text' name='firstname' value=''></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>Nachname :</td><td>	<input type='text' name='lastname' value=''></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>email :</td><td>	<input type='text' name='email' value=''></td>";
	echo "</tr>";
	echo "<tr>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><input type='submit' name='addUser' value='Hinzufügen'></td>";
	echo "<td><input type='submit' name='Cancel' value='Abbrechen'></td>";
	echo "</tr>";
	echo "</table>";
}
?>