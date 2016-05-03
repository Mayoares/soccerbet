<?php
//Zugangsdaten
$dbuser="root";		 //Benutzername fuer den MySQL-Zugang
$password="";		 //Passwort
//$host="Mayoar.rivido.de";	 //Name (IP-Adr.) des Rechners mit MySQL
$host="localhost";	 //Name (IP-Adr.) des Rechners mit MySQL
//$host="127.0.0.1";	 //Name (IP-Adr.) des Rechners mit MySQL
$dbname="emtipp2016";//Name der Datenbank


// TRIAL PHP5.5 becuase mysql_connect is deprecated
// $mysqli = new mysqli($host, $dbuser, $password, $dbname);
// if ($mysqli->connect_errno) {
// 	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
// }
// echo $mysqli->host_info . "\n";

// $mysqli = new mysqli("127.0.0.1", "user", "password", "database", 3306);
// if ($mysqli->connect_errno) {
// 	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
// }

// echo $mysqli->host_info . "\n";


//Verbindung aufbauen
$db = mysql_connect($host, $dbuser, $password) or die("Verbindung fehlgeschlagen");
//echo "$db<br>";
//Datenbank als Standard definieren
$db_selected = mysql_select_db($dbname,$db);
if (!$db_selected) {
	die ("Kann $dbname nicht benutzen : " . mysql_error());
}
//echo "selected DB name: $dbname, db_selected? $db_selected";
?>