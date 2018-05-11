<?php
// we must never forget to start the session
session_start();
$userId=$_GET["userId"];
$framepart=$_GET["framepart"];
echo "<html>";
echo "<head>";
echo "<title>Werke's Tippspiel - Tipps kontrollieren</title>";
echo "</head>";

if(strlen($userId)>0)
{
	if($framepart=="group")
	{
		echo "<frameset cols=\"50%,50%\">";
	    //echo "<frameset cols=\"38%,38%,24%\">";
		echo "	<frame src='./showGroupTipps.php5?userId=$userId&groups=Part1' name='Gruppen A-D'>";
		echo "	<frame src='./showGroupTipps.php5?userId=$userId&groups=Part2' name='Gruppen E-H'>";
	    // echo "	<frame src='./showFinalsTipps.php5?userId=$userId' name='Rest'>";
		echo "</frameset>";
	}
	else if($framepart=="finals")
	{
		echo "<frameset cols=\"100%\">";
		echo "	<frame src='./showFinalsTipps.php5?userId=$userId' name='Enrunde'>";
	}
	else if($framepart=="specials")
	{
		echo "<frameset cols=\"100%\">";
		echo "	<frame src='./showSpecialTipps.php5?userId=$userId' name='Spezial'>";
	}
	else
	{
		echo "<br> kein Frame gesetzt";
	}
}
else
{
	echo "<br> kein User gesetzt";
}
?>