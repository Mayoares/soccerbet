<?php
include_once("../../connection/dbaccess.php5");
include_once("../util/dbschema.php5");
class dbutil
{
    function getTeams($group){
        $table_teams=dbschema::teams;
        $sqlQuery="SELECT t.name FROM $table_teams t WHERE t.group='$group'";
        $sqlQueryResult=mysql_query($sqlQuery);
        return $sqlQueryResult;
    }
    
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
	
	function getRostrumPrediction($username, $rank)
	{
	    $table_championtipps=dbschema::championtipps;
	    $sqlQuery="SELECT * FROM $table_championtipps WHERE user='$username' AND rank=$rank";
	    $sqlQueryResult=mysql_query($sqlQuery);
	    $sqlResultArray=mysql_fetch_array($sqlQueryResult);
	    $teamShort=$sqlResultArray["team"];
	    return self::getTeamName($teamShort);
	}
	
	function getTopScorerPrediction($username){
	    $table_topscorertipps=dbschema::topscorertipps;
	    $sqlQueryResult=mysql_query("SELECT * FROM $table_topscorertipps WHERE user='$username'");
	    $sqlResultArray=mysql_fetch_array($sqlQueryResult);
	    $topscorer=$sqlResultArray["topscorer"];
	    return $topscorer;
	}
	
	function getTopScorerTeamPrediction($username){
		$table_teams=dbschema::teams;
		$table_topscorertipps=dbschema::topscorertipps;
		$sqlQueryResult=mysql_query("SELECT * FROM $table_topscorertipps top, $table_teams teams WHERE user='$username' AND top.team=teams.shortname");
		$sqlResultArray=mysql_fetch_array($sqlQueryResult);
		$teamName=$sqlResultArray["name"];
		return $teamName;
	}
	
	function getGoals1($userName, $matchnr){
	    $table_groupmatchtipps=dbschema::groupmatchtipps;
	    $result=mysql_query("SELECT goalsX FROM $table_groupmatchtipps WHERE user = '$userName' AND matchnr = $matchnr;");
	    $array=mysql_fetch_array($result);
	    $goals=$array["goalsX"];
	    return $goals;
	}
	function getGoals2($userName, $matchnr){
	    $table_groupmatchtipps=dbschema::groupmatchtipps;
	    $result=mysql_query("SELECT goalsY FROM $table_groupmatchtipps WHERE user = '$userName' AND matchnr = $matchnr;");
	    $array=mysql_fetch_array($result);
	    $goals=$array["goalsY"];
	    return $goals;
	}
	
}
$dbutil=new dbutil();
?>