<?php
class logger
{
	function error($text)
	{
		$HOME = "../..";
		$logdatei=fopen($HOME . "/logs/logfile.txt","a");
		fputs($logdatei, date("d.m.Y, H:i:s",time()) . ", ERROR, " . $text ."\n");
		fclose($logdatei);
	}
	
	function warn($text)
	{
		$HOME = "../..";
		$logdatei=fopen($HOME . "/logs/logfile.txt","a");
		fputs($logdatei, date("d.m.Y, H:i:s",time()) . ", WARN , " . $text ."\n");
		fclose($logdatei);
	}
	
	function info($text)
	{
		$HOME = "../..";
		$logdatei=fopen($HOME . "/logs/logfile.txt","a");
		fputs($logdatei, date("d.m.Y, H:i:s",time()) . ", INFO , " . $text ."\n");
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