<html>
<head>
<link rel='stylesheet' type='text/css' href='../../style/style.css' />
<br>
<a href='../util/loginPunktestand.php5'>zur�ck</a>
<br>
<br>
<title>Werke's Tippspiel - Tipps ALL USERS (Germany)</title>
</head>
<script type="text/javascript">
<!--
/* http://www.alistapart.com/articles/zebratables/ */
function removeClassName (elem, className) {
	elem.className = elem.className.replace(className, "").trim();
}

function addCSSClass (elem, className) {
	removeClassName (elem, className);
	elem.className = (elem.className + " " + className).trim();
}

String.prototype.trim = function() {
	return this.replace( /^\s+|\s+$/, "" );
}

function stripedTable() {
	if (document.getElementById && document.getElementsByTagName) {  
		var allTables = document.getElementsByTagName('table');
		if (!allTables) { return; }

		for (var i = 0; i < allTables.length; i++) {
			if (allTables[i].className.match(/[\w\s ]*scrollTable[\w\s ]*/)) {
				var trs = allTables[i].getElementsByTagName("tr");
				for (var j = 0; j < trs.length; j++) {
					removeClassName(trs[j], 'alternateRow');
					addCSSClass(trs[j], 'normalRow');
				}
				for (var k = 0; k < trs.length; k += 2) {
					removeClassName(trs[k], 'normalRow');
					addCSSClass(trs[k], 'alternateRow');
				}
			}
		}
	}
}

window.onload = function() { stripedTable(); }
-->
</script>
<?php
echo "<body>";
include_once("../../connection/dbaccess-local.php5");
include_once("../../general/log/log.php5");
include_once("../util/calc.php5");
include_once("../util/dbutil.php5");
include_once("../util/dbschema.php5");

$log=new viewlogger();
$log->info("clicked showAllUsersTipps.php5");


printGroupMatches();


function getUserInfo($username){
	$tableusers=dbschema::users;
	$sql="SELECT * from $tableusers WHERE username='$username'";
	$resultUser=mysql_query($sql);
	$array=mysql_fetch_array($resultUser);
	$firstname=$array["firstname"];
	$lastname=$array["lastname"];
	return "$firstname $lastname";
}

function printGroupMatches(){
	
	echo "<h3>Gruppenspiele</h3>";
	echo "<br>";
	$table_matches=dbschema::matches;
	$sql="SELECT * FROM $table_matches m WHERE m.matchtype='Gruppenspiel'";
	$sqlResult=mysql_query($sql);
	
	echo "<div id=\"tableContainer\" class=\"tableContainer\">";
	//echo "<table style=\"table-layout:fixed\" border='1' rules='all' class= \"scrollTable\">";
	echo "<table border='1' rules='all' width=\"100%\" class= \"scrollTable\">";
	echo "<thead class=\"fixedHeader\">";
	//echo "<thead>";
		//echo "<tr>";
		//echo "<th style=\"width:400px\">Spiel</th> ";
		////style="width:120px"
		//while($array=mysql_fetch_array($sqlResult)){
			//$matchnr = $array["matchnr"];
			////echo "<td></td><td> $matchnr </td><td></td>";
			//echo "<th align='center'> $matchnr </th>";
		//}
		//echo "</tr>";
	echo "<tr>";
	echo "<th style=\"width:400px\">  Paarung </th>";
	$sqlResult=mysql_query($sql);
	while($array=mysql_fetch_array($sqlResult)){
		
		$teamName1=$array["team1"];
		$teamName2=$array["team2"];
		//echo "<td align='right'>$teamName1</td> <td align='center'>-</td> <td align='left'>$teamName2</td>";
		//echo "<th align='center'>$teamName1-$teamName2</th>";
		echo "<th>$teamName1-$teamName2</th>";
	}
	echo "</tr>";
	echo "</thead>";
		
	echo "<tbody class=\"scrollContent\">";
	$table_users=dbschema::users;
	$sql="SELECT * FROM $table_users t ORDER BY lastname,firstname";
	$sqlResult=mysql_query($sql);
	while($array=mysql_fetch_array($sqlResult)){
		
		echo "<tr span=\"100%\">";
		printGroupMatchesForUser($array["username"]);
		echo "</tr>";
	}
	echo "</tbody>";
	echo "</table>";
	echo "</div>";
}
function printGroupMatchesForUser($username){
	
	$table_matches=dbschema::matches;
	$table_users=dbschema::users;
	$table_tipps=dbschema::groupmatchtipps;
	echo "<tr>";
	echo "<td>" . getUserInfo($username) . "</td>";
	$sql="SELECT * FROM $table_matches m LEFT JOIN $table_tipps gmt ON m.matchnr=gmt.matchnr AND gmt.user='$username' WHERE m.matchtype='Gruppenspiel' ";
	$sqlResult=mysql_query($sql);
	while($array=mysql_fetch_array($sqlResult)){
		
		$matchnr=$array["matchnr"];
		$tippGoalsTeam1=$array["goalsX"];
		$tippGoalsTeam2=$array["goalsY"];
		$evaluationDone=$array["evaluationDone"];
		if($evaluationDone === "T"){
			// get points
			$points = $array["score"];
			if($points == NULL){ // should not happen when evaluation is already done, but use default then
				echo "<td align='center'>$tippGoalsTeam1 : $tippGoalsTeam2</td>";
			} else if($points == 0){ // print white
				echo "<td><div align='center' style=\"background-color:white\">$tippGoalsTeam1 : $tippGoalsTeam2</div></td>";
			} else if($points == 2){ // print light blue
				echo "<td><div align='center' style=\"background-color:CCFFFF\">$tippGoalsTeam1 : $tippGoalsTeam2</div></td>";
			} else if($points == 4){ // print light green
				echo "<td><div align='center' style=\"background-color:CCFF99\">$tippGoalsTeam1 : $tippGoalsTeam2</div></td>";
			}
		} else {
			//echo "<td align='right'>$tippGoalsTeam1</td><td align='center'> : </td><td align='left'>$tippGoalsTeam2</td>";
			echo "<td align='center'>$tippGoalsTeam1 : $tippGoalsTeam2</td>";
		}
	}
	echo "</tr>";
}
?>