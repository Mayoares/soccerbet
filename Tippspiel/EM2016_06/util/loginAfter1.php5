<html>
<head>
<link rel='stylesheet' type='text/css' href='../../style/style.css' />
<title>Werke's Tippspiel Login </title>
</head>
<body>
<center>
<p><img src="../pics/WerkesWM_WebTipp_2014_Klein.png" alt="WM2014-Logo"></p>

<h2>
<a href='../anybody/showUserRanks.php5'>Punktestand</a>
</h2>

<table CELLSPACING=20>
<thead>
<tr>
<th><u>Newsletter</u></th><th><u>Regeln</u></th><th><u>Tipps aller Teilnehmer</u></th>
</tr>
</thead>
<td>
<a href='../news/WM-Newsletter-2014-06-06.docx'>WM-Newsletter #1</a>
<br>
<a href='../news/WM-Newsletter-2014-06-12.docx'>WM-Newsletter #2</a>
<br>
<a href='../news/WM-Newsletter-2014-06-17.docx'>WM-Newsletter #3</a>
<br>
<a href='../news/WM-Newsletter-2014-06-23.docx'>WM-Newsletter #4</a>
<br>
<a href='../news/WM-Newsletter-2014-06-30.docx'>WM-Newsletter #5</a>
<br>
<a href='../news/WM-Newsletter-2014-07-07.docx'>WM-Newsletter #6</a>
<br>
<a href='../news/WM-Newsletter-2014-07-10.docx'>WM-Newsletter #7</a>
<br>
<a href='../news/WM-Newsletter-2014-07-18.docx'>WM-Newsletter #8</a>
</td>
<td>
<a href='../docs/Spielregeln_WM.pdf'>Werke's Tippspiel-Regeln</a>
</td>
<td>
<a href='../anybody/showAllUsersGroupTipps.php5?userId=$userId'>Gruppenspieltipps</a>
<br>
<a href='../anybody/showAllUsersGroupRankTipps.php5?userId=$userId'>Platzierungstipps</a>
<br>
<a href='../anybody/showAllUsersFinalmatchTipps.php5?userId=$userId'>Finalspieltipps</a>
<br>
<a href='../anybody/showAllUsersSpecialTipps.php5?userId=$userId'>Spezialtipps</a>
</td>
</table>

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
</center>

</body>
</html>

<?php

function allUsersAsOption($optionName) {
	include_once("../../connection/dbaccess-local.php5");
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
		$log->info("keine passenden Datens�tze gefunden");
		echo "keine passenden Datens�tze gefunden";
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