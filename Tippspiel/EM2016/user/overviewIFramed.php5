<?php
// we must never forget to start the session
session_start();

print_r($_GET);
$parsedParams=$_GET["mainParams"];
$userId=$parsedParams['userId'];
echo "userId=$userId";

$subpage=$parsedParams['subpage'];
$subpageName=$subpage['name'];
$subpageParams=$subpage['params'];

//$userId=$_GET["userId"];
if(strlen($userId)==0)
{
	header("Location: ../util/login.php5");
	exit;
}
include_once("../util/dbutil.php5");
include_once("../share/ScoreDefinitions.php5");
$userName=$dbutil->getUserName($userId);
?>
<html>
<head>
<link rel='stylesheet' type='text/css' href='../../style/style-WM2014.css' />
<title>Kick-Tipp</title>
</head>
<body>
<script type="text/javascript">
function FrameAendern () {
  parent.location.href = "../util/login.php5";
}
</script>

<?php
echo "<br>";
echo "Eingelogged als <b>$userName</b>";
echo "&nbsp;&nbsp;";
echo "<br>";
echo "<a href='CheckLogout.php5?userId=$userId' target='Daten'/><FONT SIZE=5><b>Ausloggen</b></FONT>";
echo "<br>";

// POPUP trial :
//echo "<a target=\"popup\" onclick=\"window.open" .
//		"('', 'popup', 'width=580,height=360,scrollbars=no, toolbar=no,status=no, resizable=yes,menubar=no,location=no,directories=no,top=10,left=10')" .
//		"\"href=\"CheckLogout.php5?userId=$userId\"><FONT SIZE=4><b>Logout</b></FONT></a>";

$params=createSubParams('userInfo.php5', $userId);
echo "<br><a href=overviewIFramed.php5?$params>Home</a>";
//echo "<br><a href='./userInfo.php5?userId=$userId' target='Daten'>Home</a>";
echo "<br>";
if($userName != "test")
{
	$params=createSubParams('changePassword.php5', $userId);
	echo "<br><a href=overviewIFramed.php5?$params>Passwort ändern</a>";
	//echo "<a href='./changePassword.php5?userId=$userId' target='Daten'>Passwort ändern</a>";
}
echo "<br>";

function createSubParams($subPageName, $userId){
	$mainParams = array(
			'userId' => $userId,
			'subpage' => array(		
				'name' => $subPageName,
				'params' => array(	
			    	'userId' => $userId,
				)
			)
	);
	$query = http_build_query(array('mainParams' => $mainParams));
	echo "<br>query=$query";
	return $query;
}	
function createGroupParams($userId, $group){
	$mainParams = array(
			'userId' => $userId,
			'subpage' => array(		
				'name' => 'Group.php5',
				'params' => array(	
			    	'userId' => $userId,
			    	'group' => $group
				)
			)
	);
	$query = http_build_query(array('mainParams' => $mainParams));
	echo "<br>query=$query";
	return $query;
}	
	
 
echo "<br>";
echo "<br>";
echo "<br>";
echo "<FONT SIZE=5><b>Tippen:</b></FONT>";
echo "<br>";
//header("Location: overviewIFramed.php5?userId=$userId&subpage='Group.php5");

$groupParamsA=createGroupParams($userId, 'A');
echo "<br><a href=overviewIFramed.php5?$groupParamsA>Gruppe A</a>";
$groupParamsB=createGroupParams($userId, 'B');
echo "<br><a href=overviewIFramed.php5?$groupParamsB>Gruppe B</a>";
$groupParamsC=createGroupParams($userId, 'C');
echo "<br><a href=overviewIFramed.php5?$groupParamsC>Gruppe C</a>";
$groupParamsD=createGroupParams($userId, 'D');
echo "<br><a href=overviewIFramed.php5?$groupParamsD>Gruppe D</a>";
$groupParamsE=createGroupParams($userId, 'E');
echo "<br><a href=overviewIFramed.php5?$groupParamsE>Gruppe E</a>";
$groupParamsF=createGroupParams($userId, 'F');
echo "<br><a href=overviewIFramed.php5?$groupParamsF>Gruppe F</a>";
// echo "<br><a href='Group.php5?userId=$userId&group=A' target='Daten'>Gruppe A</a>";
// echo "<br><a href='Group.php5?userId=$userId&group=B' target='Daten'>Gruppe B</a>";
// echo "<br><a href='Group.php5?userId=$userId&group=C' target='Daten'>Gruppe C</a>";
// echo "<br><a href='Group.php5?userId=$userId&group=D' target='Daten'>Gruppe D</a>";
// echo "<br><a href='Group.php5?userId=$userId&group=E' target='Daten'>Gruppe E</a>";
// echo "<br><a href='Group.php5?userId=$userId&group=F' target='Daten'>Gruppe F</a>";
if(constant('championshipType') == ScoreDefinitions::CHAMPIONSHIP_TYPE_WM)
{
	echo "<br><a href='Group.php5?userId=$userId&group=G' target='Daten'>Gruppe G</a>";
	echo "<br><a href='Group.php5?userId=$userId&group=H' target='Daten'>Gruppe H</a>";
}
echo "<br>";
$params=createSubParams('Finals.php5', $userId);
echo "<br><a href=overviewIFramed.php5?$params>Endrunde</a>";
// echo "<br><a href='Finals.php5?userId=$userId' target='Daten'>Endrunde</a>";
echo "<br>";
$params=createSubParams('Champions.php5', $userId);
if(constant('championshipType') == ScoreDefinitions::CHAMPIONSHIP_TYPE_WM)
{
	echo "<br><a href=overviewIFramed.php5?$params>Weltmeister & Torschützenkönig</a>";
// 	echo "<br><a href='Champions.php5?userId=$userId' target='Daten'>Weltmeister & Torschützenkönig</a>";
}
else
{
	echo "<br><a href=overviewIFramed.php5?$params>Europameister & Torschützenkönig</a>";
// 	echo "<br><a href='Champions.php5?userId=$userId' target='Daten'>Europameister & Torschützenkönig</a>";
}
echo "<br>";
echo "<br>";
echo "<br>";
echo "<FONT SIZE=5><b>Tipps kontrollieren:</b></FONT>";
echo "<br>";
echo "<br><a href='./showAllTipps.php5?userId=$userId&framepart=group' target='Daten'>";
echo "<b>Meine Gruppen-Tipps</b>";
echo "<br>";
echo "<br><a href='./showAllTipps.php5?userId=$userId&framepart=rest' target='Daten'>";
echo "<b>Meine Final-Tipps</b>";
echo "</a>";

//echo "<iframe src='./userInfo.php5?userId=$userId' width='1200' height='800' align='left' frameborder='0' style='position:absolute;Left:315;bottom:0'>";
echo "<br>";
// $subpage=$_GET['subpage'];
// $subpage = str_replace('\'', '', $subpage);
// echo "subpage=$subpage";
//$subpagePlusParams="$subpage?userId=$userId";
//echo "subpagePlusParams=$subpagePlusParams";
//<iframe src="<?php echo $subpagePlusParams; 
//" width='1200' height='800' align='left' frameborder='0' style='position:absolute;Left:350;bottom:0'></iframe>
//$query = http_build_query(array('params' => $subpageParams));
$query = http_build_query($subpageParams);
echo "$subpageName?$query";
echo "<iframe src='$subpageName?$query' width='1200' height='800' align='left' frameborder='0' style='position:absolute;Left:750;bottom:0'></iframe>"; 
echo "</body>";
echo "</html>";
?>