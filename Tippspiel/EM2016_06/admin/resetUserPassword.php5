<?php
include_once("../../connection/dbaccess-local.php5");
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
		echo "<a href='./overviewAdmin.php5?userId=$adminuserId'>zurück zur Übersicht</a>";
	}
	else
	{
		$message = "Für dich wurde ein neues Passwort generiert.<br>" .
	     "Dein neues Passwort lautet: <b>$password</b>.<br>" .
		 "Bitte bald einloggen und ändern.<br>";
		mail($email, "Werke's Tippspiel Passwort", $message, "From: WERKEs-Werke's Tippspiel\n" . "Content-Type: text/html; charset=iso-8859-1\n");
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
	echo "Passwort von User '<b>$username</b>' wirklich zurücksetzen?";
	echo "<br>";
	echo "<br>";
	echo "<form method='POST' action='resetUserPassword.php5?userId=$adminuserId&ResetUsername=$username'>";
	echo "<input type='submit' name='resetUserPasswordReally' value='OK'></td>";
	echo "&nbsp; &nbsp;";
	echo "<input type='submit' name='Cancel' value='Abbrechen'></td>";
}

?>