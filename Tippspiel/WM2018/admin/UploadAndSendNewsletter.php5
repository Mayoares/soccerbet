<?php
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
	$log=new adminlogger();
	
	/* Add the original filename to our target path.  
	Result is "uploads/filename.extension" */
	$log->info("target_path:" + $pdfdir);
	$pdfdir= dirname(getcwd()) . "/pdf/";
	$target_path = $pdfdir . basename( $_FILES['uploadedfile']['name']); 
	$filename = basename( $_FILES['uploadedfile']['name']);
	$log->info("filename:" + $filename);
	
	echo "<br>";
	echo "<a href='./overviewAdmin.php5?userId=$adminuserId'>zur&uuml;ck zur &Uuml;bersicht</a>";
	echo "<br>";
	echo "<br>";
	echo "Datei wird hochgeladen ...<br>";
	
	$inipath = php_ini_loaded_file();
	if ($inipath) {
		$log->info("Loaded php.ini: ' . $inipath");
	} else {
		$log->warn("A php.ini file is not loaded");
	}
	
// 	if (is_uploaded_file($_FILES['uploadedfile']['tmp_name'])) {
// 		echo "Datei ". $_FILES['uploadedfile']['name'] ." erfolgreich hochgeladen.\n";
// // 		echo "Anzeige des Inhalts\n";
// // 		readfile($_FILES['uploadedfile']['tmp_name']);
// 	} else {
// 		echo "Unzulaessiger Dateiupload: ";
// 		echo "Dateiname '". $_FILES['uploadedfile']['tmp_name'] . "'.";
// 	}
	
// 	echo "<br>";
// 	echo "<br>";
// 	echo 'Weitere Debugging Informationen:';
// 	print_r($_FILES);
// 	echo "<br>";
// 	echo "<br>";
	
	if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
	    echo "Die Datei ".  basename( $_FILES['uploadedfile']['name']). " wurde hochgeladen.<br>";
	    $log->info("Datei wurde hochgeladen.");
	    echo "Starte verschicken ...<br><br>";
	    sendFileToAll($filename, $target_path);
	    echo "<br>Verschicken fertig.<br>";
	    echo "<br>";
		echo "<a href='./overviewAdmin.php5?userId=$adminuserId'>zur&uuml;ck zur &Uuml;bersicht</a>";
	    echo "<br>";
	} else{
	    echo "Fehler beim Hochladen der Datei, bitte nochmal versuchen!";
	}
}

function sendFileToAll($filename, $target_path){
	
	include_once("../../connection/dbaccess.php5");
	include_once("../../general/log/log.php5");
	include_once("../util/dbschema.php5");
	$table_users = dbschema::users;
	$sql="SELECT * FROM $table_users";
	$log=new adminlogger();	
	$log->info($sql);
	$sqlResult=mysql_query($sql);
	while($sqlArray=mysql_fetch_array($sqlResult)){
		$email = $sqlArray["email"];
		if(strcontains($email, "@")){
// 		if($email == "andreas.grotemeyer@gmx.de"){
			sendFile($filename, $target_path, $email);
			$log->info("Sent file to " + $email + ".");
		} else {
			$log->info("Did not send file to " + $email + ".");
			echo "not sent to $email<br>";
		}
	}
}

function sendFile($filename, $target_path, $empfaenger){
	
	$betreff = "Werke's Tippspiel - Newsletter"; // Betreff

	$dateiname = $target_path;
	$dateiname_mail = $filename;

	$id = md5(uniqid(time()));
	$dateiinhalt = fread(fopen($dateiname, "r"), filesize($dateiname));

	// Absender Name und E-Mail Adresse
	$header = "From: tippspiel@mayoar.rivido.de\n";
	$header .= "Content-Type: multipart/mixed; boundary=$id\n\n";
	$header .= "This is a multi-part message in MIME format\n";
	$header .= "--$id\n";
	$msg .= "Lieber Tippspiel-Teilnehmer, \n\nim Anhang Werke's Tippspiel - Newsletter."; // Inhalt der E-Mail (Body)
	$msg .= "\n--$id";
// 	$kopf .= "\nContent-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document; name=$dateiname_mail\n";
	$msg .= "\nContent-Type: application/pdf; name=\"{$dateiname_mail}\"\n";
	$msg .= "Content-Transfer-Encoding: base64\n";
	$msg .= "Content-Disposition: attachment; filename=\"{$dateiname_mail}\"\n\n";
	$msg .= chunk_split(base64_encode($dateiinhalt));
	$msg .= "\n--$id--";
	mail($empfaenger, $betreff, $msg, $header); // E-Mail versenden
	
	echo "Mail gesendet an '$empfaenger' mit Betreff '$betreff'.<br>";
}

function strcontains($haystack,$needle) {
	if  (strpos($haystack,$needle)!==false)
	return true;
	else
	return false;
}
?>