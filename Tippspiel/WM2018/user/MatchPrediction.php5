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