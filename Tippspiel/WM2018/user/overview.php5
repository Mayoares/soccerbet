<?php
// we must never forget to start the session
session_start();

$userId=$_GET["userId"];
if(strlen($userId)==0)
{
	header("Location: ../util/login.php5");
	exit;
}
include_once("../../connection/dbaccess.php5");
include_once("../util/dbutil.php5");
$userName=$dbutil->getUserName($userId);
?>
<html>
<head>
<link rel='stylesheet' type='text/css' href='../../style/style-EM2016.css' />
<title>Werke's Tippspiel - Navigation</title>
</head>
<body>
<script type="text/javascript">
function FrameAendern () {
  parent.location.href = "../util/login.php5";
}
</script>

<?php
echo "Eingelogged als <b>$userName</b>";
echo "&nbsp;&nbsp;";
echo "<br>";
echo "<a href='CheckLogout.php5?userId=$userId' target='Daten'/><FONT SIZE=5><b>Logout</b></FONT>";
echo "<br>";

// POPUP trial :
//echo "<a target=\"popup\" onclick=\"window.open" .
//		"('', 'popup', 'width=580,height=360,scrollbars=no, toolbar=no,status=no, resizable=yes,menubar=no,location=no,directories=no,top=10,left=10')" .
//		"\"href=\"CheckLogout.php5?userId=$userId\"><FONT SIZE=4><b>Logout</b></FONT></a>";

echo "<br><a href='./userInfo.php5?userId=$userId' target='Daten'>Home</a>";
echo "<br>";
echo "<br>";
if($userName != "test")
{
	echo "<a href='./changePassword.php5?userId=$userId' target='Daten'>Passwort &auml;ndern</a>";
}

echo "<br>";
echo "<br>";
echo "<FONT SIZE=5><b>Tipps abgeben:</b></FONT>";
echo "<br>";
echo "<br><a href='Group.php5?userId=$userId&group=A' target='Daten'>Gruppe A</a>";
echo "<br>";
echo "<br><a href='Group.php5?userId=$userId&group=B' target='Daten'>Gruppe B</a>";
echo "<br>";
echo "<br><a href='Group.php5?userId=$userId&group=C' target='Daten'>Gruppe C</a>";
echo "<br>";
echo "<br><a href='Group.php5?userId=$userId&group=D' target='Daten'>Gruppe D</a>";
echo "<br>";
echo "<br><a href='Group.php5?userId=$userId&group=E' target='Daten'>Gruppe E</a>";
echo "<br>";
echo "<br><a href='Group.php5?userId=$userId&group=F' target='Daten'>Gruppe F</a>";
// echo "<br>";
// echo "<br><a href='Group.php5?userId=$userId&group=G' target='Daten'>Gruppe G</a>";
// echo "<br>";
// echo "<br><a href='Group.php5?userId=$userId&group=H' target='Daten'>Gruppe H</a>";
echo "<br>";
echo "<br><a href='Finals.php5?userId=$userId' target='Daten'>Endrunde</a>";
echo "<br>";
echo "<br><a href='Champions.php5?userId=$userId' target='Daten'>Spezial-Tipps</a>";
echo "<br>";
echo "<br>";
echo "<br>";
echo "<FONT SIZE=5><b>Tipps kontrollieren:</b></FONT>";
echo "<br>";
echo "<br><a href='./showAllTipps.php5?userId=$userId&framepart=group' target='Daten'>";
echo "Meine Gruppenspiel-Tipps";
echo "<br>";
echo "<br><a href='./showAllTipps.php5?userId=$userId&framepart=finals' target='Daten'>";
echo "Meine Endrunden-Tipps";
echo "<br>";
echo "<br><a href='./showAllTipps.php5?userId=$userId&framepart=specials' target='Daten'>";
echo "Meine Spezial-Tipps";
echo "</a>";

echo "</body>";
echo "</html>";
?>