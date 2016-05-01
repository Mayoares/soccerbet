<?php
include_once("../../connection/dbaccess-local.php5");
include_once("../util/dbschema.php5");
class dbutil
{
	function getUserName($userid){
		$table_users=dbschema::users;
		$result=mysql_query("SELECT username FROM $table_users WHERE userid = '$userid'");
		$array=mysql_fetch_array($result);
		$userName=$array["username"];
		return $userName;
	}
	
	function getTeamName($shortname) {
		$table_teams=dbschema::teams;
		$result=mysql_query("SELECT t.name FROM $table_teams t WHERE t.shortname='$shortname'");
		$array=mysql_fetch_array($result);
		$name=$array["name"];
		return $name;
	}
	
	function getShortName($teamname){
		$table_teams=dbschema::teams;
		$sqlResult=mysql_query("SELECT t.shortname FROM $table_teams t WHERE t.name='$teamname'");
		$array=mysql_fetch_array($sqlResult);
		$shortname=$array["shortname"];
		return $shortname;
	}
	
	function getPicName($teamname){
		$table_teams=dbschema::teams;
		$sqlResult=mysql_query("SELECT t.logofile FROM $table_teams t WHERE t.name='$teamname'");
		$array=mysql_fetch_array($sqlResult);
		$picname=$array["logofile"];
		return $picname;
	}
}
$dbutil=new dbutil();
?>