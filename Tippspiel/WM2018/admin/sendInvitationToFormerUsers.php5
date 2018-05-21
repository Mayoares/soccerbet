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
	echo "else if";
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
	echo "<a href='./overviewAdmin.php5?userId=$adminuserId'>zur&uuml;ck zur &Uuml;bersicht</a>";
	echo "<br>";
	echo "<br>";

// 	$table_users_last_em=dbschema::users_last_em;
 	$table_users_last_wm=dbschema::users_last_wm;
//	$table_users_last_wm=dbschema::users_last_test;
	
	$sql="SELECT lastname, firstname, username, email, invitationSent FROM $table_users_last_wm";
	$resultLastUsers=mysql_query($sql);
	while($array=mysql_fetch_array($resultLastUsers)){
		
		$invitationSent=$array["invitationSent"];
		$lastname=$array["lastname"];
		$firstname=$array["firstname"];
		$username=$array["username"];
		$email=$array["email"];
		if($invitationSent!=0){
			
			echo "Einladung wurde bereits verschickt. Keine Aktion ausgef&uuml;hrt f&uuml;r User $username.";
			continue;
		}
		
		
		$id = md5(uniqid(time()));
		$betreff = "Werke's Tippspiel - WM 2018";
		$header = "From: tippspiel@mayoar.rivido.de\n";
		$header .= "MIME-Version: 1.0\n";
		$header .= "Content-Type: multipart/mixed; boundary=$id\n";
		$header .= "This is a multi-part message in MIME format\n";
		$header .= "--$id\n";
		$header .= "Content-Type: text/plain\n";
		$header .= "Content-Transfer-Encoding: 8bit\n";
		// Inhalt der E-Mail (Body)
		$content = "Liebe(r) $firstname $lastname,";
		$content .= "\n";
		$content .= "\nDu hast 2014, als die Fußball-WM in Brasilien stattfand, bei Werke's Tippspiel teilgenommen.";
		$content .= "\nVielleicht möchtest du heuer bei der Weltmeisterschaft 2018 in Russland wieder dabei sein ...";
		$content .= "\n";
		$content .= "\nAlle Infos findest du auf http://www.mayoar.rivido.de";
		$content .= "\n";
		$content .= "\nAuf deine Teilnahme beim Tippen freuen sich Werke, Mayoar und Robl!";
		// Body Ende
		mail($email, $betreff, $content, $header); // E-Mail versenden
		$printOut = "Einladung zum Tippspiel wird an $firstname $lastname (Benutzername:$username, email:'$email') gesendet.";
		echo $printOut;
		$log->info("Sent invitation for $firstname $lastname (Benutzername:$username) to '$email'");
		
		$sql="UPDATE `$table_users_last_wm` SET `invitationSent` = '1' WHERE `$table_users_last_wm`.`username` = '$username'";
		$result=mysql_query($sql);
		$log->info("Updated in DB (Benutzername:$username) invitationSent to 1.");
	}
	
	echo "<br>";
	echo "<br>";
	echo "<a href='./overviewAdmin.php5?userId=$adminuserId'>zur&uuml;ck zur &Uuml;bersicht</a>";
	
}

?>