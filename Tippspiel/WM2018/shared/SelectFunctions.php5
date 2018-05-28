<?php
include_once("../util/dbutil.php5");
include_once("../util/dbschema.php5");

class Select
{
    function getRostrumPrediction($username, $rank)
    {
        $table_championtipps=dbschema::championtipps;
        $sqlQuery="SELECT * FROM $table_championtipps WHERE user='$username' AND rank=$rank";
        $sqlQueryResult=mysql_query($sqlQuery);
        $sqlResultArray=mysql_fetch_array($sqlQueryResult);
        $teamShort=$sqlResultArray["team"];
        $dbutil=new dbutil();
        return $dbutil->getTeamName($teamShort);
    }
}
$Select=new Select();
?>