<?php
include_once("../../connection/dbaccess.php5");
include_once("../../general/log/log.php5");
$adminuserId=$_GET["userId"];
$username=$_POST["SelectedUsername"];
$resetUsername=$_GET["ResetUsername"];
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
	else if(isset($_POST["resetUserPasswordReally"]))
	{
		resetUserPassword($resetUsername, $adminuserId);
	}
	else
	{
		ask($username, $adminuserId);
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

function ask($username, $adminuserId){

	$log=new adminlogger();
	$log->info("ask(remove '" . $username . "' by '" .$adminuserId."')");
	promptResetUserPassword($username, $adminuserId);
}

function resetUserPassword($resetUsername, $adminuserId)
{
	
	$log=new adminlogger();
	$log->info("run resetUserPassword('".$resetUsername."' by '" . $adminuserId . "')");
	include_once("../util/dbschema.php5");
	$table_users=dbschema::users;
	
	$password=gen_md5_password(8);
	echo "<br>";

	$sql="SELECT email FROM $table_users WHERE username = '$resetUsername'";
	$log->info($sql);
	$sqlResult=mysql_query($sql);
	$sqlArray=mysql_fetch_array($sqlResult);
	$email = $sqlArray["email"];
	
	$sql="UPDATE $table_users SET password = '$password' WHERE username = '$resetUsername'";
	$log->info($sql);
	$sqlResult=mysql_query($sql);
	if(!$sqlResult)
	{
		echo 'Datenbankfehler. MIST!<br>';
		echo mysql_error();
		echo "<br>";
		echo "<br>";
		echo "<a href='./overviewAdmin.php5?userId=$adminuserId'>zur&uuml;ck zur &Uuml;bersicht</a>";
	}
	else
	{
		$betreff = "Werke's Tippspiel - Passwort zur�ckgesetzt";
		$kopf = "From: werkestippspiel\n";
		$kopf .= "MIME-Version: 1.0\n";
		$kopf .= "Content-Type: multipart/mixed; boundary=$id\n\n";
		$kopf .= "This is a multi-part message in MIME format\n";
		$kopf .= "--$id\n";
		$kopf .= "Content-Type: text/plain\n";
		$kopf .= "Content-Transfer-Encoding: 8bit\n\n";
		// Inhalt der E-Mail (Body)
		$kopf .= "F�r dich wurde ein neues Passwort generiert.";
		$kopf .= "\n\nDein neues Passwort lautet: $password";
		$kopf .= "\n\nBitte bald einloggen und �ndern.";
		$kopf .= "\n\nDirektlink zum Tippspiel: http://mayoar.rivido.de";
		$kopf .= "\n--$id";
		// Body Ende
		mail($email, $betreff, "", $kopf); // E-Mail versenden
		
		echo "<br>User $username hat ein neues Passwort erhalten.<br>";
		echo "<br>Das Passwort wurde an seine eMail-Adresse '$email' geschickt.<br>";
		echo "<form method=\"POST\" action=\"overviewAdmin.php5?userId=$adminuserId\">";
		echo "<br>";
		echo "<input type=\"submit\" name=\"ok\" value=\"OK\">";
		echo "</form>";
	}
}

function gen_md5_password($len = 6)
{
    // function calculates 32-digit hexadecimal md5 hash
    // of some random data
    return substr(md5(rand().rand()), 0, $len);
}


function promptResetUserPassword($username, $adminuserId)
{
	echo "<br>";
	echo "<br>";
	echo "Passwort von User '<b>$username</b>' wirklich zur&uuml;cksetzen?";
	echo "<br>";
	echo "<br>";
	echo "<form method='POST' action='resetUserPassword.php5?userId=$adminuserId&ResetUsername=$username'>";
	echo "<input type='submit' name='resetUserPasswordReally' value='OK'></td>";
	echo "&nbsp; &nbsp;";
	echo "<input type='submit' name='Cancel' value='Abbrechen'></td>";
}

?>