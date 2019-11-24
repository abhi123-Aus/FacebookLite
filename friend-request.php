<?php

require_once('db.class.php');

// Resume existing session
session_start();

$objDb = new db();
$conn = $objDb -> connect_orcale();

$option = $_GET['log'];
$friend_sname = $_GET['sname'];
$email_id = $_SESSION['email_validated'];
$sname = $_SESSION['user_validated'];

$sql_query1 = "select * from MEMBERS where SCREEN_NAME = '$friend_sname'";
$stid1 = oci_parse($conn, $sql_query1);
$result1 = oci_execute($stid1);
	
if ($result1) {
	$friend_request = oci_fetch_array($stid1, OCI_BOTH);
	$friend_email = $friend_request['EMAIL_ID'];
	
	if ($option == "accept") {
		$sql_query2 = "insert into FRIENDSHIP (MEMBERS_EMAIL_ID, MEMBERS_EMAIL_ID1, START_DATE) values ('$friend_email', '$email_id', (select SYSDATE from DUAL)) ";
		$sql_query3 = "insert into FRIENDSHIP (MEMBERS_EMAIL_ID, MEMBERS_EMAIL_ID1, START_DATE) values ('$email_id', '$friend_email', (select SYSDATE from DUAL)) ";
		$sql_query4 = "update FRIENDSHIPREQUEST set REQUESTSTATUS = 'Accepted' where MEMBERS_EMAIL_ID = '$email_id' and MEMBERS_EMAIL_ID1 = '$friend_email'";
		
		$stid2 = oci_parse($conn, $sql_query2);
		$stid3 = oci_parse($conn, $sql_query3);
		$stid4 = oci_parse($conn, $sql_query4);
		
		oci_execute($stid2);
		oci_execute($stid3);
		oci_execute($stid4);
		
		oci_free_statement($stid2);
		oci_free_statement($stid3);
		oci_free_statement($stid4);
	}
	
	if ($option == "decline") {
		$sql_query5 = "update FRIENDSHIPREQUEST set REQUESTSTATUS = 'Rejected' where MEMBERS_EMAIL_ID = '$email_id' and MEMBERS_EMAIL_ID1 = '$friend_email'";
		$stid5 = oci_parse($conn, $sql_query5);
		oci_execute($stid5);
		
		oci_free_statement($stid5);
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