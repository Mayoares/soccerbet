<?php
//Zugangsdaten
$dbuser="web133";		 //Benutzername für den MySQL-Zugang
//$password="RGTsHGi3";		 //Passwort
$password="wm2010";		 //Passwort
//$host="Mayoar.rivido.de";	 //Name (IP-Adr.) des Rechners mit MySQL
$host="localhost";	 //Name (IP-Adr.) des Rechners mit MySQL
$dbname="usr_web133_1";//Name der Datenbank

//Verbindung aufbauen
$db = mysql_connect($host, $dbuser, $password) or die("Verbindung fehlgeschlagen");
//Datenbank als Standard definieren
mysql_select_db($dbname,$db);
?>
