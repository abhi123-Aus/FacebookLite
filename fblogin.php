<?php

require_once('db.class.php');

// Initialize new or resume existing session
session_start();

if (isset($_POST['email']) && isset($_POST['password'])) {
	$email_id = $_POST['email'];
	$password = $_POST['password'];
	
	$sql_query = "select * from MEMBERS where EMAIL_ID = '".$email_id."' and PASSWORD = '".$password."'";
	
	$objDb = new db();
	$conn = $objDb -> connect_orcale();
	
	//Prepares an Oracle statement for execution
	$stid = oci_parse($conn, $sql_query);
	
	//Executes a statement
	$result = oci_execute($stid);
	
	if ($result) {
		//Returns number of rows affected during statement execution
		$row = oci_fetch_array($stid, OCI_BOTH);
		
		//Fetches multiple rows from a query into a two-dimensional array
		if ($row != false) {
			$_SESSION['loggedin'] = true;
			$_SESSION['email'] = $row['EMAIL_ID'];
			$_SESSION['sname'] = $row['SCREEN_NAME'];
			
			header('Location: fbhome.php?sname='.$_SESSION['sname']);
		}
		else {
			header('Location: fbindex.php?erro=1');
		}
	}
	else {
		$errormsg = oci_error($stid); 
        echo $errormsg['message'];
		echo 'Error executing query, please contact your site administrator';
	}
	
	oci_free_statement($stid);
	oci_close($conn);
}

?>