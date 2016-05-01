<?php
class UserScores {
	
	function getScoreSumUser($user){

		$scoregroupmatchtipps = $this->getScoreSum($user, dbschema::groupmatchtipps);
		$scoregroupranktipps = $this->getScoreSum($user, dbschema::groupranktipps);
		$scorefinalparticipantstipps = $this->getScoreSumColumn($user, dbschema::users, "finalparticipantscore", "username");
		$scorefinalmatchtipps = $this->getScoreSum($user, dbschema::finalmatchtipps);
		$scorechampiontipps = $this->getScoreSum($user, dbschema::championtipps);
		$scoretopscorertipps = $this->getScoreSum($user, dbschema::topscorertipps);
		$scoreSum = $scoregroupmatchtipps + $scoregroupranktipps +$scorefinalparticipantstipps+ $scorefinalmatchtipps + $scorechampiontipps + $scoretopscorertipps;
		return $scoreSum;
	}
	
	function getScoreSum($user, $table){
		$sql="SELECT user, sum(score) as scoreSum FROM $table WHERE user='$user'";
		//$log=new logger();
		//$log->info($sql);
		$sqlResult=mysql_query($sql);
		//$log->info("result= " . $sqlResult);
		$user = mysql_result($sqlResult, 0, "user");
		$scoreSum = mysql_result($sqlResult, 0, "scoreSum");
		//echo "<br>$user, $scoreSum";
		return $scoreSum;
	}
	
	function getScoreSumColumn($user, $table, $columnNameScore, $columnNameUser){
		$sql="SELECT $columnNameUser as user, sum($columnNameScore) as scoreSum FROM $table WHERE $columnNameUser='$user'";
		//$log=new logger();
		//$log->info($sql);
		$sqlResult=mysql_query($sql);
		//$log->info("result= " . $sqlResult);
		$user = mysql_result($sqlResult, 0, "user");
		$scoreSum = mysql_result($sqlResult, 0, "scoreSum");
		//echo "<br>$user, $scoreSum";
		return $scoreSum;
	}
}
?>