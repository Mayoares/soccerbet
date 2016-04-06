<?php
include_once("../util/dbutil.php5");
include_once("../../general/log/log.php5");
include_once("../../general/mail/MailFunctions.php5");
include_once("../util/dbutil.php5");
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
		echo "<form method=\"POST\" action=\"overviewAdmin.php5?userId=$adminuserId\">";
		echo "<br>";
		echo "<input type=\"submit\" name=\"ok\" value=\"OK\">";
		echo "</form>";
	}
}

?>