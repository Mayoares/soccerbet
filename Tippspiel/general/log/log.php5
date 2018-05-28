<?php
class logger
{
// 		 __FILE__ 
// 		 __LINE__
// 		 __DIR__
// 		 __FUNCTION__
// 		 __CLASS__
// 		 __METHOD__
		
	function error($text)
	{
	    self::internalLog("ERROR", "---", $text);
	}
	
	function warn($text)
	{
	    self::internalLog("WARN", "---", $text);
	}
	
	function info($text)
	{
	    self::internalLog("INFO", "---", $text);
	}
	
	function infoCall($filename, $text)
	{
	    self::internalLog("INFO", $filename, $text);
	}
	
	function internalLog($level, $filename, $text)
	{
	    $HOME = "../..";
	    $logdatei=fopen($HOME . "/logs/logfile.txt","a");
	    fputs($logdatei, date("d.m.Y-H:i:s",time()) . "|$level|$filename| $text \n");
	    fclose($logdatei);
	}
}

class adminlogger
{
	function error($text)
	{
		$HOME = "../..";
		$logdatei=fopen($HOME . "/logs/adminlogfile.txt","a");
		fputs($logdatei, date("d.m.Y, H:i:s",time()) . ", ERROR, " . $text ."\n");
		fclose($logdatei);
	}
	
	function warn($text)
	{
		$HOME = "../..";
		$logdatei=fopen($HOME . "/logs/adminlogfile.txt","a");
		fputs($logdatei, date("d.m.Y, H:i:s",time()) . ", WARN , " . $text ."\n");
		fclose($logdatei);
	}
	
	function info($text)
	{
		$HOME = "../..";
		$logdatei=fopen($HOME . "/logs/adminlogfile.txt","a");
		fputs($logdatei, date("d.m.Y, H:i:s",time()) . ", INFO , " . $text ."\n");
		fclose($logdatei);
	}
}

class viewlogger
{
	function error($text)
	{
		$HOME = "../..";
		$logdatei=fopen($HOME . "/logs/viewlogfile.txt","a");
		fputs($logdatei, date("d.m.Y, H:i:s",time()) . ", ERROR, " . $text ."\n");
		fclose($logdatei);
	}
	
	function warn($text)
	{
		$HOME = "../..";
		$logdatei=fopen($HOME . "/logs/viewlogfile.txt","a");
		fputs($logdatei, date("d.m.Y, H:i:s",time()) . ", WARN , " . $text ."\n");
		fclose($logdatei);
	}
	
	function info($text)
	{
		$HOME = "../..";
		$logdatei=fopen($HOME . "/logs/viewlogfile.txt","a");
		fputs($logdatei, date("d.m.Y, H:i:s",time()) . ", INFO , " . $text ."\n");
		fclose($logdatei);
	}
}
?>