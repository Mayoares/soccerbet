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
	echo "<br><p class=\"info\">Username fehlt. Bitte Eingabe korrigieren!</p>";
	promptLogin("");
}
else if(empty($_POST["password"]))
{
	echo "<br><p class=\"info\">Passwort fehlt. Bitte Eingabe korrigieren!</p>";
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
			echo "<br><p class=\"info\">Falsches Passwort. Bitte Eingabe korrigieren!</p>";
			$postPW=$_POST["password"];
			promptLogin($username);
		}
		else if ($username==="admin")
		{
			header("Location: ../admin/overviewAdmin.php5?userId=$userId");
		}
		else
		{
			header("Location: ../user-after/FramedLogin.php5?userId=$userId");
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

<link rel='stylesheet' type='text/css' href='../../style/style-WM2018.css' />
<title>Werke's Tippspiel - Login</title>
</head>
<body>

<center>

<p><img src="../pics/EM 2016 Tippspiel Logo.PNG" class="image" width="300" alt="Logo_EM_2016">

<div class="block">
	<p><a href="../../index.html"> <h2>Home</h2> </a> </p>
</div>

<div class="block">
  	<h1><font color="#ffffff">Login</font></h1>
  	<hr>
<?php 
	echo "<table>";
	echo "<form method='POST' action='loginAfter.php5'>";
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

</body>
</html>