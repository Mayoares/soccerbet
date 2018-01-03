<?php
class calc
{
	function calcWinner($goalsTeam1,$goalsTeam2)
	{
		if($goalsTeam1>$goalsTeam2)
		{
			return 1;
		}
		else if($goalsTeam1<$goalsTeam2)
		{
			return 2;
		}
		return 0;
	}
}
$calc=new calc();
?>