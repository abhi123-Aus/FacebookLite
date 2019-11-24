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
	
	$sql_query2 = "insert into FRIENDSHIPREQUEST (MEMBERS_EMAIL_ID, MEMBERS_EMAIL_ID1, REQUESTDATE, REQUESTSTATUS) values ('$friend_email', '$email_id', (select SYSDATE from DUAL), 'Pending')";
		
	$stid2 = oci_parse($conn, $sql_query2);
	$result2 = oci_execute($stid2);
	
	if ($result2) {
		header('Location: fbhome.php?sname='.$_SESSION['user_validated']);
		echo 'Friend request sent successfully!';
	} else {
		echo 'Error sending friend request!';
		$errormsg = oci_error($stid2);
		echo $errormsg['message'];
		echo 'Error executing query, please contact your site administrator';
	}
	
	oci_free_statement($stid2);
} else {
		$errormsg = oci_error($stid1);
		echo $errormsg['message'];
		echo 'Error executing query, please contact your site administrator';
}

oci_free_statement($stid1);
oci_close($conn);

?>