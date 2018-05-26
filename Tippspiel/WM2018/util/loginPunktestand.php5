<?php
// we must never forget to start the session
session_start();
?>
<!DOCTYPE html>
<html>

<head>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<!-- Mobile viewport -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">

<!-- Facivon 
<link rel="shortcut icon" href="images/favicon.ico"  type="image/x-icon"> -->

<link rel='stylesheet' type='text/css' href='../../style/style-current.css' />
<title>Werke's Tippspiel - Home</title>
</head>

<body>

<center>

<p><img src="../pics/Tippspiel-Logo.PNG" class="image" width="300" alt="Logo">

<div class="block">
	<p><a href="../../index.html"> <h2>Home</h2> </a> </p>
</div>

<div class="block">
	<h2><a href='../anybody/showUserRanks.php5'>Punktestand</a></h2>
</div>

<div class="block">
	<h1><font color="#ffffff">Tipps im Detail</font></h1>
	<hr>
	<p><h2><a href='../anybody/showAllUsersGroupTipps.php5?userId=$userId'>Gruppenspiel-Tipps</a></h2></p>
	<br>
	<p><h2><a href='../anybody/showAllUsersGroupRankTipps.php5?userId=$userId'>Platzierungs-Tipps</a></h2></p>
	<br>
	<p><h2><a href='../anybody/showAllUsersFinalmatchTipps.php5?userId=$userId'>Endrunden-Tipps</a></h2></p>
	<br>
	<p><h2><a href='../anybody/showAllUsersSpecialTipps.php5?userId=$userId'>Spezial-Tipps</a></p></h2></p>
	<p><form method="POST" action="../anybody/showAllTippsAndScore.php5"></p>
	
	<p>Alle Tipps von
	<td bgcolor=slategray><select name='SelectedUsername'>
  	<?php allUsersAsOption('User');?>


	</td><input type='submit' name='showUser' value='anzeigen'/></form>
</div>

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