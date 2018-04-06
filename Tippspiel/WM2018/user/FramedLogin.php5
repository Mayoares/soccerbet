<?php
session_start();
$userId=$_GET["userId"];
//$logout=$_GET["logout"];
// if($logout==1)
// {
// 	header("Location: ../util/login.php5");
// 	exit;
// }
include_once("../util/dbutil.php5");
$userName=$dbutil->getUserName($userId);
if(!isset($_GET['userId']))
{
	header("Location: ../util/login.php5");
	exit;
}
?>
<title>Werke's Tippspiel - Tippabgabe</title>
<link rel="stylesheet" type="text/css" href="../../style/style-WM2018.css" />
<?php
echo "<frameset cols='280,*'>";
echo "<frame src='./overview.php5?userId=$userId' name='Navigation'>";
echo "<frame src='./userInfo.php5?userId=$userId' name='Daten'>";
echo "</frameset>";
?>