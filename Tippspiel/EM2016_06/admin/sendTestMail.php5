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
	if(!isset($_POST["email"]))
	{
		echo "<br>Keine eMail-Adresse eingegeben.";
	}
	else
	{
		$email=$_POST["email"];

		sendTestMail($email, $adminuserId);
	}
}

function sendTestMail($email, $adminuserId)
{
	$log=new adminlogger();
	$log->info("sendTestMail(". $email.")");
		
	$id = md5(uniqid(time()));
	$betreff = "Werke's Tippspiel - Testmail";
	$kopf = "From: werkestippspiel\n";
	$kopf .= "MIME-Version: 1.0\n";
	$kopf .= "Content-Type: multipart/mixed; boundary=$id\n\n";
	$kopf .= "This is a multi-part message in MIME format\n";
	$kopf .= "--$id\n";
	$kopf .= "Content-Type: text/plain\n";
	$kopf .= "Content-Transfer-Encoding: 8bit\n\n";
	// Inhalt der E-Mail (Body)
	$kopf .= "Dies ist eine Testmail.";
	$kopf .= "\n\nDirektlink zum Tippspiel: http://mayoar.rivido.de";
	$kopf .= "\n--$id";
	// Body Ende
	mail($email, $betreff, "", $kopf); // E-Mail versenden
	$log->info("Sent test email to '$email'");
	
	echo "<form method=\"POST\" action=\"overviewAdmin.php5?userId=$adminuserId\">";
	echo "<br>";
	echo "<input type=\"submit\" name=\"ok\" value=\"OK\">";
	echo "</form>";
}

?>