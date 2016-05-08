<!DOCTYPE HTML>
<head>
<link rel='stylesheet' type='text/css' href='../../style/style-EM2016.css' />
<title>Werke's Tippspiel - Punktestand</title>
</head>
<body>
<center>
<h1>Werke's Tippspiel zur Europameisterschaft 2016 in Frankreich</h1>
<p><img src="../pics/EM 2016 Tippspiel Logo.png" class="image" width="400" alt="Logo_EM_2016">
<h2>
<a href='../anybody/showUserRanks.php5'>Punktestand</a>
</h2>
<br>
<a href='../news/WM-Newsletter-2014-06-06.docx'>WM-Newsletter #1</a>
<br>
<br>
<br>
<br>
<a href='../docs/Spielregeln_WM.pdf'>Werke's Tippspiel-Regeln</a>
<br>
<br>
<br>
<h3>Tipps im Detail ansehen</h3>
<a href='../anybody/showAllUsersGroupTipps.php5?userId=$userId'>Gruppenspieltipps</a>
<br>
<a href='../anybody/showAllUsersGroupRankTipps.php5?userId=$userId'>Platzierungstipps</a>
<br>
<a href='../anybody/showAllUsersFinalmatchTipps.php5?userId=$userId'>Finalspieltipps</a>
<br>
<a href='../anybody/showAllUsersSpecialTipps.php5?userId=$userId'>Spezialtipps</a>
<br>
<br>
<form method="POST" action="../anybody/showAllTippsAndScore.php5">
Alle Tipps von
<td bgcolor=slategray><select name='SelectedUsername'>
<?php
allUsersAsOption("User");
?>
</td>
<br>
<input type='submit' name='showUser' value='anzeigen'/>
</form>
<br>
</center>

</body>
</html>

<?php

function allUsersAsOption($optionName) {
	include_once("../../connection/dbaccess.php5");
	include_once("../../general/log/log.php5");
	include_once("../util/dbschema.php5");
	$log=new logger();	
	$log->info("Viewed login page");
	$table_users=dbschema::users;
	$sqlUsers="SELECT * FROM $table_users ORDER BY username";
	//$log->info($sqlUsers);
	$sqlUsersResult=mysql_query($sqlUsers);
	$numUsers=mysql_num_rows($sqlUsersResult);
	if ($numUsers==0)
	{
		$log->info("keine passenden Datens&auml;tze gefunden");
		echo "keine passenden Datens&auml;tze gefunden";
	}
	else
	{
		//$log->info("Anzahl User=". $numUsers);
		for ($i=0; $i<$numUsers; $i++)
		{
			$name = mysql_result($sqlUsersResult, $i, "username");
			if($name!='admin' && $name!='real')
			{
				echo"<option name=$optionName >$name</option>";
			}
		}
	}
}
?>