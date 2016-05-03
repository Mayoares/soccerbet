<?php
include_once("../../connection/dbaccess.php5");
include_once("../../general/log/log.php5");
$adminuserId=$_GET["userId"];
$username=$_POST["SelectedUsername"];
$removeUsername=$_GET["RemoveUsername"];
if(isset($_POST["Cancel"]))
{
	$target="../admin/overviewAdmin.php5?userId=$adminuserId";
	header("Location:$target");
	exit;
}
else if(strlen($adminuserId)==0)
{
	$log=new adminlogger();
	$log->warn("AdminuserId was empty");
	header("Location: ../util/login.php5");
	exit;
}
else
{
	if(!isAdmin($adminuserId))
	{
		header("Location: ../util/login.php5");
		exit;
	}
	else if(isset($_POST["removeUserReally"]))
	{
		removeUser($removeUsername, $adminuserId);
	}
	else
	{
		ask($username, $adminuserId);
	}
}

function isAdmin($adminuserId){
	
	include_once("../util/dbutil.php5");
	$adminuserName=$dbutil->getUserName($adminuserId);
	if($adminuserName==="admin")
	{
		return true;
	}
	else
	{
		$log=new adminlogger();
		$log->warn("Wrong user (" . $adminuserName . ") tried to add user.");
		return false;
	}
}

function ask($username, $adminuserId){

	$log=new adminlogger();
	$log->info("ask(remove '" . $username . "' by '" .$adminuserId."')");
	promptRemoveUser($username, $adminuserId);
}

function removeUser($removeUsername, $adminuserId)
{
	
	$log=new adminlogger();
	$log->info("run removeUser('".$removeUsername."' by '" . $adminuserId . "')");
	include_once("../util/dbschema.php5");
	$table_users=dbschema::users;
	
	if(countTipps($removeUsername) > 0)
	{
		echo "User hat schon was getippt.<br>";
		echo "Es m&uuml;ssen vorher noch alle seine Tipps gel&ouml;scht werden.<br>";
		echo "Bitte an Mayoar wenden!<br>";
		echo "<br>";
		echo "<a href='./overviewAdmin.php5?userId=$adminuserId'>zur&uuml;ck zur &Uuml;bersicht</a>";
	}
	else
	{

		// remove from DB
		$sql="DELETE FROM $table_users WHERE username = '$removeUsername'";
		$log=new adminlogger();
		$log->info($sql);
		$sqlResult=mysql_query($sql);
		if(!$sqlResult)
		{
			echo 'Datenbankfehler. MIST!<br>';
			echo mysql_error();
			echo "<br>";
			echo "<br>";
			echo "<a href='./overviewAdmin.php5?userId=$adminuserId'>zur&uuml;ck zur &Uuml;bersicht</a>";
		}
		else
		{
			echo "<br>User $username wurde gel&ouml;scht.<br>";
			echo "<form method=\"POST\" action=\"overviewAdmin.php5?userId=$adminuserId\">";
			echo "<br>";
			echo "<input type=\"submit\" name=\"ok\" value=\"OK\">";
			echo "</form>";
		}
	}
}

function promptRemoveUser($username, $adminuserId)
{
	echo "Diese Funktion ist bisher nur m&ouml;glich, wenn der User noch <b>nichts</b> getippt hat. Wenn doch, bitte an den Administrator wenden.";
	echo "<br>";
	echo "<br>";
	echo "User '<b>$username</b>' wirklich l&ouml;schen?";
	echo "<br>";
	echo "<br>";
	echo "<form method='POST' action='removeUser.php5?userId=$adminuserId&RemoveUsername=$username'>";
	echo "<input type='submit' name='removeUserReally' value='OK'></td>";
	echo "&nbsp; &nbsp;";
	echo "<input type='submit' name='Cancel' value='Abbrechen'></td>";
}

function countTipps($username)
{
	$table_groupranktipps=dbschema::groupranktipps;
	$table_groupmatchtipps=dbschema::groupmatchtipps;
	$table_finalmatchtipps=dbschema::finalmatchtipps;
	$table_championtipps=dbschema::championtipps;
	$table_topscorertipps=dbschema::topscorertipps;
	
	$grt = countTippsFromTable($username, $table_groupranktipps);
	echo "Platzierungstipps=$grt (User=$username) <br>";
	$gmt = countTippsFromTable($username, $table_groupmatchtipps);
	echo "Gruppenspieltipps=$gmt (User=$username) <br>";
	$fmt = countTippsFromTable($username, $table_finalmatchtipps);
	echo "Finalspieltipps=$fmt (User=$username) <br>";
	$cht = countTippsFromTable($username, $table_championtipps);
	echo "Championtipps=$cht (User=$username) <br>";
	$tst = countTippsFromTable($username, $table_topscorertipps);
	echo "Topscorertipps=$tst (User=$username) <br>";
	$tippcount = $grt + $gmt + $fmt + $cht + $tst;
	
	$log=new adminlogger();	
	$log->info("Overall tippcount=$tippcount (user=$username)");
	echo "<b>Gesamttippanzahl=$tippcount (User=$username)</b> <br> <br>";
	return $tippcount;
}

function countTippsFromTable($username, $table)
{
	include_once("../util/dbutil.php5");
	$log=new adminlogger();	
	
	$sql="SELECT COUNT(*) as tippcount FROM $table WHERE user='$username'";
	$log->info($sql);
	$sqlResult=mysql_query($sql);
	echo mysql_error();
	if(mysql_error()!=0)
	{
		$log->error($sqluser);
	}
	$array=mysql_fetch_array($sqlResult);
	$tippcount=$array["tippcount"];
	return $tippcount;
}
?>