<?php

require_once('db.class.php');

// Resume existing session
session_start();

//if(!isset($_SESSION['email'])){
//	header('Location: index.php?erro=1');
//}

$objDb = new db();
$conn = $objDb -> connect_orcale();

$comment = $_POST['comment'];
$parentpostid = $_POST['parentpostid'];
$email_id = $_SESSION['email_validated'];
$sname = $_SESSION['user_validated'];

$sql_query1 = "select * from POST";
$stid1 = oci_parse($conn, $sql_query1);
$result1 = oci_execute($stid1);

if ($result1) {
	$rows = oci_fetch_all($stid1, $array1);
	
	$sql_query2 = "insert into POST (POSTID, BODY, POSTTIME_STAMP, POST_POSTID, PARENTPOSTID, MEMBERS_EMAIL_ID) values (($rows + 1), '$comment', (select CURRENT_TIMESTAMP from DUAL), $parentpostid, $parentpostid, '$email_id')";
	$stid2 = oci_parse($conn, $sql_query2);
	$result2 = oci_execute($stid2);
	
	if ($result2) {
		oci_free_statement($stid2);
	} else {
		$errormsg = oci_error($stid2);
		echo $errormsg['message'];
		echo 'Error executing query, please contact your site administrator';
	}
} else {
	$errormsg = oci_error($stid1);
	echo $errormsg['message'];
	echo 'Error executing query, please contact your site administrator';
}

header('Location: fbhome.php?sname='.$_SESSION['user_validated']);

oci_free_statement($stid1);
oci_close($conn);

?>