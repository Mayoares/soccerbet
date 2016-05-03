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
	
	//Datenbankconnection schliessen
	mysql_close();
}

function promptLogin($username){
	?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//DE"
            "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel='stylesheet' type='text/css' href='../../style/style-EM2016.css' />
<title>Werke's Tippspiel Login </title>
</head>
<body>
<center>
<p><img src="../pics/WM-Logo-2012 (Klein).png" alt="WM2012-Logo"></p>

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
<br>
<br>
<a href='../docs/Spielregeln_WM.pdf'>Werke's Tippspiel-Regeln</a>
<br>
<!--
<a href='../anybody/showUserRank.php5'>Punktestand</a>
-->
</center>

<table cellspacing="1" cellpadding="0" border="0" style="background-color: #ABCDDD" width="122"><tr><td align="center" style="background-color : #ABCDDD;"><a href="http://www.meteo24.de/wetter/49X7482.html" style="text-decoration: none;" target="_blank"><font face="Verdana" size="1" color="#000000" style="text-decoration: none; font-weight: bold;">Wetter Reischach</font></a></td></tr><tr><td align="center"></td></tr><tr><td align="center" height="15" style="background-color : #ABCDDD;"><a href="http://www.meteo24.de/" style="text-decoration: none;" target="_blank"><font face="Verdana" size="1" color="#000000">&copy; meteo24.de</font></a></td></tr></table>

</body>
</html>