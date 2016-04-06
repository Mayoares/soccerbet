<?php
session_start();
$userId=$_GET["userId"];
//echo "userId=$userId<br>";

// TODO problem : Notice: Undefined index: logout in D:\xampp\htdocs\soccerbet\EM2016\user\FramedLogin.php5 on line 5
// $logout=$_GET["logout"];
// echo "logout=$logout<br>";
// if($logout==1)
// {
// 	header("Location: ../util/login.php5");
// 	exit;
// }

include_once("../util/dbutil.php5");
$userName=$dbutil->getUserName($userId);
//echo "userName=$userName<br>";
if(!isset($_GET['userId']))
{
	header("Location: ../util/login.php5");
	exit;
}
?>

<html>
<title>Werkes Kick-Tipp</title>
<link rel="stylesheet" type="text/css" href="../../style/style-WM2014.css" />
<?php
// echo "<frameset cols='280,*'>"; // erste Spalte (Navigation) 280 Pixel breit, Rest angepasst
// echo "<frame src='./overview.php5?userId=$userId' name='Navigation'>";
// echo "<frame src='./userInfo.php5?userId=$userId' name='Daten'>";
// echo "</frameset>";
// ?>
</html>



<!-- <html> -->
<!-- <title>Werkes Kick-Tipp</title> -->
<!-- <link rel="stylesheet" type="text/css" href="../../style/style-WM2014.css" /> -->
<?php
//echo "<iframe src='./overview.php5?userId=$userId' width='280' height='800'>";
echo "<iframe src='./userInfo.php5?userId=$userId' width='800' height='800' align='right' frameborder='0' style='position:absolute;Left:15;bottom:-10'>";
?>
<!-- </html> -->