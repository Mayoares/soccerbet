<?php
class citation
{
	function printCitation($group){

		$table_groupcitations=dbschema::groupcitations;
		$result=mysql_query("SELECT * FROM $table_groupcitations gc WHERE gc.group = '$group'");
		$array=mysql_fetch_array($result);
		$citation=$array["citation"];
		$author=$array["author"];
		echo "<b>&bdquo;$citation&rdquo;</b>  ";
		echo $author;
		echo "<br>";
	}
}
$citation=new citation();
?>