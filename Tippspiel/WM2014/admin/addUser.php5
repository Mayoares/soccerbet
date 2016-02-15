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
	//$password = "123";
	$password = generatePassword();
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
		
		$id = md5(uniqid(time()));
		$betreff = "WERKEs WM-Tippspiel Login-Info";
		$content = "From: WERKEs-WM-Tipp\n";
		$content .= "MIME-Version: 1.0\n";
		$content .= "Content-Type: multipart/mixed; boundary=$id\n\n";
		$content .= "This is a multi-part message in MIME format\n";
		$content .= "--$id\n";
		$content .= "Content-Type: text/plain\n";
		$content .= "Content-Transfer-Encoding: 8bit\n\n";
		// Inhalt der E-Mail (Body)
		$content .= "Lieber Tippspiel-Teilnehmer $firstname $lastname,";
		$content .= "\n\ndeine Logindaten lauten";
		$content .= "\n\nBenutzername: $username "; 
		$content .= "\nPasswort    : $password ";
		$content .= "\n\nDas Passwort wurde automatisch generiert und sollte nach dem ersten Login geändert werden."; 
		$content .= "\n\nDirektlink zum Tippspiel: http://mayoar.rivido.de/WM2014/util/login.php5";
		$content .= "\n--$id";
		// Body Ende
		mail($email, $betreff, "", $content); // E-Mail versenden
		$printOut = "eMail mit initialem Passwort für $firstname $lastname (Benutzername:$username) an '$email' gesendet.";
		echo $printOut;
		$log->info("Sent email with initial password for $firstname $lastname (Benutzername:$username) to '$email'");
		
		$adminEMail = "andreas.grotemeyer@gmail.com";
		$adminContent = "From: WERKEs-WM-Tipp\n";
		$adminContent .= "MIME-Version: 1.0\n";
		$adminContent .= "Content-Type: multipart/mixed; boundary=$id\n\n";
		$adminContent .= "This is a multi-part message in MIME format\n";
		$adminContent .= "--$id\n";
		$adminContent .= "Content-Type: text/plain\n";
		$adminContent .= "Content-Transfer-Encoding: 8bit\n\n";
		// Inhalt der E-Mail (Body)
		mail($adminEMail, $printOut, "", $adminContent);
		
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

function generatePassword()
{
	// ein sechsstelliges Passwort generieren
  	$passwort = "";
  	$pool = "qwertzupasdfghkyxcvbnm23456789";
  	srand ((double)microtime()*1000000);
  	for($n = 0; $n <= 5; $n++) {
    	$passwort .= substr($pool,(rand()%(strlen ($pool))), 1);
   	}
    return $passwort;
}

function promptAddUser($adminuserId)
{
	echo "<form method='POST' action='addUser.php5?userId=$adminuserId'>";
	echo "<table>";
	echo "<tr>";
	echo "<td>User-Name:</td><td>	<input type='text' name='username' value='' size=30></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>Vorname :</td><td>	<input type='text' name='firstname' value='' size=30></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>Nachname :</td><td>	<input type='text' name='lastname' value='' size=30></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>email :</td><td>	<input type='text' name='email' value='' size=80></td>";
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