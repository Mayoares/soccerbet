<?php
include_once("../log/log.php5");

function sendMail($Empfaenger, $Mailbetreff, $Mailnachricht){
	
	$Sender = "andreas.grotemeyer@gmx.de";
	$timeAndDate = date("d.m.Y H:i:s");
	$log=new logger();	
	$log->info($timeAndDate . " Empfaenger=$Empfaenger Sender=$Sender Mailbetreff=$Mailbetreff.\n");	
	mail($Empfaenger, $Mailbetreff, $Mailnachricht, "From:$Sender\n" . "Content-Type: text/html; charset=iso-8859-1\n");
}
?>