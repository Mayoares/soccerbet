<?php
include_once("../../general/log/log.php5");

function sendMail($recipient, $subject, $message){
	
	$id = md5(uniqid(time()));
	$content = "From: werkestippspiel\n";
	$content .= "MIME-Version: 1.0\n";
	$content .= "Content-Type: multipart/mixed; boundary=$id\n\n";
	$content .= "This is a multi-part message in MIME format\n";
	$content .= "--$id\n";
	$content .= "Content-Type: text/plain\n";
	$content .= "Content-Transfer-Encoding: 8bit\n\n";
	$content .= $message;
	$content .= "\n--$id";
		
	$sender = "Werke's Tippspiel";
	$log=new adminlogger();	
	$log->info($timeAndDate . " Empfaenger=$recipient Sender=$sender Mailbetreff=$subject.\n");	
	if(mail($recipient, $subject, $message, "From:$sender\n" . "Content-Type: text/html; charset=iso-8859-1\n")){
		return true;
	}
	else {
		return false;
	}
}

function sendUserLogin($firstname, $lastname, $email, $username, $password){

	$subject = "Werke's Tippspiel - Login-Info";
	// Inhalt der E-Mail (Body)
	$message .= "Lieber Tippspiel-Teilnehmer $firstname $lastname,";
	$message .= "\n\ndeine Logindaten lauten";
	$message .= "\n\nBenutzername: $username "; 
	$message .= "\nPasswort    : $password ";
	$message .= "\n\nDas Passwort wurde automatisch generiert und sollte nach dem ersten Login ge&auml;ndert werden."; 
	$message .= "\n\nDirektlink zum Tippspiel: http://mayoar.rivido.de/EM2016/util/login.php5";
	// Body Ende
	$mailSent = sendMail($email, $subject, $message);
	
	$log=new adminlogger();
	if($mailSent){
		$printOut = "eMail mit initialem Passwort f&uuml;r $firstname $lastname (Benutzername:$username) an '$email' gesendet.";
		$log->info("Sent email with initial password for $firstname $lastname (Benutzername:$username) to '$email'");
		echo $printOut;
	}
	else {
		$printOut = "eMail f&uuml;r $firstname $lastname (Benutzername:$username) konnte nicht gesendet werden.";
		$log->error("Sending email with initial password for $firstname $lastname (Benutzername:$username) to '$email' not successful.");
		echo "<font color=\"red\"><b> $printOut </b></font>";
	}
}

function sendNewUserLoginInfoToAdmin($firstname, $lastname, $username, $adminEMail){

	$subject = "Tippspiel: User angelegt : $username";
	sendMail($adminEMail, $subject, "Neuer User '$username' ($firstname $lastname).");
}	

function sendTestMail($email, $adminuserId)
{
	$log=new adminlogger();
	$log->info("sendTestMail(". $email.")");
		
	$id = md5(uniqid(time()));
	$subject = "WERKEs Tippspiel - Testmail";
	$message .= "Dies ist ein Testmail.";
	// TODO : param EM 2016
	$message .= "\n\nDirektlink zum Tippspiel: http://mayoar.rivido.de";
	
	$mailSent = sendMail($email, $subject, $message);
	if($mailSent){
		$feedback = "Sent test email to '$email'";
		echo $feedback;
		$log->info($feedback);
	}
	else {
		$feedback = "FAILED to send test email to '$email'";
		echo "<font color=\"red\"><b> $feedback </b></font>";
		$log->error($feedback);
	}
}
		
?>