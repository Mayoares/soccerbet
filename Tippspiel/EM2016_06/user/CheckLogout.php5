<?php
session_start();
$userId=$_GET["userId"];
include_once("../../connection/dbaccess.php5");
include_once("../util/dbschema.php5");
if(isset($_POST["Cancel"]))
{
	$target="./overview.php5?userId=$userId";
	header("Location:$target");
	exit;
}
else if(isset($_POST["logout"]))
{
	
}
else 
{
	echo "<html>";
	echo "<head>";
	echo "<link rel='stylesheet' type='text/css' href='../../style/style-EM2016.css' />";
	echo "<title> Werke's Tippspiel - Logout</title>";
	echo "</head>";
	echo "<br>";
	// echo "<font color=\"red\"><b>Hast du alle Tipps vollst&auml;ndig ausgef&uuml;llt und nichts vergessen?</b></font>";
	echo "<p class=\"info\">Hast du alle Tipps vollst&auml;ndig ausgef&uuml;llt und nichts vergessen?</p>";
	echo "<br>";
	echo "<br>";
	echo "Hm, nochmal ";
	echo "<a href='./showAllTipps.php5?userId=$userId&framepart=group' target='Daten'>Meine Gruppen-Tipps</a> und ";
	echo "<a href='./showAllTipps.php5?userId=$userId&framepart=rest' target='Daten'>Meine Endrunden-Tipps</a> kontrollieren?";
	echo "<br>";
	echo "<br>";
	echo "<a href='../util/login.php5' target='Daten' onclick='FrameAendern()'/><FONT SIZE=5><b>Logout</b></FONT>";
    echo "<br>";
}

function getPassword($userid){
	
	$table_users=dbschema::users;
	$result=mysql_query("SELECT * FROM $table_users WHERE userid = '$userid'");
	$array=mysql_fetch_array($result);
	$password=$array["password"];
	return $password;
}
?>

<script type="text/javascript">
function FrameAendern () {
  parent.location.href = "../util/login.php5";
}
</script>
