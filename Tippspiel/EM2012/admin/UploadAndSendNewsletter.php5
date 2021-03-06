<?php

// Where the file is going to be placed 
$target_path = "../news/";

/* Add the original filename to our target path.  
Result is "uploads/filename.extension" */
$target_path = $target_path . basename( $_FILES['uploadedfile']['name']); 
$filename = basename( $_FILES['uploadedfile']['name']);

echo "Datei wird hochgeladen ...<br>";

if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
    echo "Die Datei ".  basename( $_FILES['uploadedfile']['name']). " wurde hochgeladen.<br>";
    echo "Starte verschicken ...<br><br>";
    sendFileToAll($filename, $target_path);
    echo "<br>Verschicken fertig.<br>";
} else{
    echo "There was an error uploading the file, please try again!";
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
		//if($email == "andreas.grotemeyer@gmx.de"){
			sendFile($filename, $target_path, $email);
		} else {
			echo "not sent to $email<br>";
		}
	}
}

function sendFile($filename, $target_path, $empfaenger){
	
	$betreff = "EM-Newsletter"; // Betreff

	$dateiname = $target_path;
	$dateiname_mail = $filename;

	$id = md5(uniqid(time()));
	$dateiinhalt = fread(fopen($dateiname, "r"), filesize($dateiname));

	// Absender Name und E-Mail Adresse
	$kopf = "From: WERKEs-EM-Tipp\n";
	$kopf .= "MIME-Version: 1.0\n";
	$kopf .= "Content-Type: multipart/mixed; boundary=$id\n\n";
	$kopf .= "This is a multi-part message in MIME format\n";
	$kopf .= "--$id\n";
	$kopf .= "Content-Type: text/plain\n";
	$kopf .= "Content-Transfer-Encoding: 8bit\n\n";
	$kopf .= "Liebe Tippspiel-Teilnehmer, anbei WERKE's EM-Newsletter."; // Inhalt der E-Mail (Body)
	$kopf .= "\n--$id";
	// Content-Type: image/gif, image/jpeg, image/png in MIME-Typen - selfHtml.org
	$kopf .= "\nContent-Type: image/gif; name=$dateiname_mail\n";
	$kopf .= "Content-Transfer-Encoding: base64\n";
	$kopf .= "Content-Disposition: attachment; filename=$dateiname_mail\n\n";
	$kopf .= chunk_split(base64_encode($dateiinhalt));
	$kopf .= "\n--$id--";
	mail($empfaenger, $betreff, "", $kopf); // E-Mail versenden
	
	echo "Mail gesendet an '$empfaenger' mit Betreff '$betreff'.<br>";
	
	//mail($email, "EM-Tipp Passwort", $message, "From: WERKEs-EM-Tipp\n" . "Content-Type: text/html; charset=iso-8859-1\n");
	
}

function strcontains($haystack,$needle) {
	if  (strpos($haystack,$needle)!==false)
	return true;
	else
	return false;
}
?>