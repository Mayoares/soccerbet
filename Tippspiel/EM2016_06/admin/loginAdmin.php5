<?php 
//$userId=$_GET["userId"];
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
	echo "<br><p class=\"info\">Admin-Username fehlt. Bitte Eingabe korrigieren!</p>";
	promptLogin("");
}
else if(empty($_POST["password"]))
{
	echo "<br><p class=\"info\">Admin-Passwort fehlt. Bitte Eingabe korrigieren!</p>";
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
		
		// Passwort ueberpruefen
		if($_POST["password"] != $password)
		{
			echo "<br><p class=\"info\">Falsches Admin-Passwort. Bitte Eingabe korrigieren!</p>";
			$postPW=$_POST["password"];
// 			$mylog = new logger;
// 			$mylog->error("Post-Passwort=$postPW, DB-Passwort=$password");
// 			$mylog->error("SQL-Query: $sql1");
			promptLogin($username);
		}
		else if ($username==="admin")
		{
			header("Location: ../admin/overviewAdmin.php5?userId=$userId");
		}
		else
		{
			echo "<br><p class=\"info\">Sorry! You are not allowed to login here!</p>";
			promptLogin("");
			//header("Location: ../user/FramedLogin.php5?userId=$userId");
			//header("Location: ../user/overview.php5?userId=$userId");
		}
	}
	
	//Datenbankconnection schliessen
	mysql_close();
}

function promptLogin($username){
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

<link rel='stylesheet' type='text/css' href='../../style/style-EM2016.css' />
<title>Werke's Tippspiel - Admin-Login</title>
</head>
<body>

<center>

<p><img src="../pics/EM 2016 Tippspiel Logo.PNG" class="image" width="300" alt="Logo_EM_2016">

<div class="block">
	<p><a href="../../index.html"> <h2>Home</h2> </a> </p>
</div>

<div class="block">
  	<h1><font color="#ffffff">Admin-Login</font></h1>
  	<hr>
<?php 
	echo "<table>";
	echo "<form method='POST' action='loginAdmin.php5'>";
	echo "<tr>";
	echo "<td>Benutzername</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td> <input type='text' size='30' name='username' value='$username'></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>Passwort </td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><input type='password' size='30' name='password' value=''></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><input type='submit' name='login' value='Anmelden'></td>";
	echo "</tr>";
	echo "</table>";
	}
	
?>
</p>
</div>

<br>
<br>
<!--
<a href='../anybody/showUserRank.php5'>Punktestand</a>
-->
<br>
</center>

<!--
<table cellspacing="1" cellpadding="0" border="0" style="background-color: #042C50" width="122"><tr><td align="center" style="background-color : #042C50;"><a href="http://www.meteo24.de/wetter/49X10168.html" style="text-decoration: none;" target="_blank"><font face="Verdana" size="1" color="#FFCC00" style="text-decoration: none; font-weight: bold;">Wetter Berlin</font></a></td></tr><tr><td align="center"></td></tr><tr><td align="center" height="15" style="background-color : #042C50;"><a href="http://www.meteo24.de/" style="text-decoration: none;" target="_blank"><font face="Verdana" size="1" color="#FFCC00">&copy; meteo24.de</font></a></td></tr></table>
-->

</body>
</html>