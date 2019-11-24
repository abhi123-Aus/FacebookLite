<?php

require_once('db.class.php');

// Resume existing session
session_start();

$objDb = new db();
$conn = $objDb -> connect_orcale();

$friend_sname = $_GET['sname'];
$email_id = $_SESSION['email_validated'];
$sname = $_SESSION['user_validated'];

$sql_query1 = "select * from MEMBERS where SCREEN_NAME = '$friend_sname'";
$stid1 = oci_parse($conn, $sql_query1);
$result1 = oci_execute($stid1);

if ($result1) {
	$friend_request = oci_fetch_array($stid1, OCI_BOTH);
	$friend_email = $friend_request['EMAIL_ID'];
	
	$sql_query2 = "delete from FRIENDSHIP where MEMBERS_EMAIL_ID = '".$email_id."' and MEMBERS_EMAIL_ID1 = '".$friend_email."'";
	$sql_query3 = "delete from FRIENDSHIP where MEMBERS_EMAIL_ID = '".$friend_email."' and MEMBERS_EMAIL_ID1 = '".$email_id."'";
		
	$stid2 = oci_parse($conn, $sql_query2);
	$stid3 = oci_parse($conn, $sql_query3);
	
	$result2 = oci_execute($stid2);
	$result3 = oci_execute($stid3);
	
	if ($result2 && $result3) {
		header('Location: fbhome.php?sname='.$_SESSION['user_validated']);
		echo 'Blocked friend successfully!';
	} else {
		echo 'Error blocking friend!';
		$errormsg = oci_error($stid2);
		echo $errormsg['message'];
		echo 'Error executing query, please contact your site administrator';
	}
	
	oci_free_statement($stid2);
	oci_free_statement($stid3);
} else {
		$errormsg = oci_error($stid1);
		echo $errormsg['message'];
		echo 'Error executing query, please contact your site administrator';
}

header('Location: fbhome.php?sname='.$_SESSION['user_validated']);

oci_free_statement($stid1);
oci_close($conn);

?>