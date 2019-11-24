<?php

require_once('db.class.php');

// Initialize new or resume existing session
session_start();

$objDb = new db();
$conn = $objDb -> connect_orcale();


if (isset($_POST['email'])) {
	$emailID = $_POST['email'];
	$fname = $_POST['fname'];
	$sname = $_POST['sname'];
	$dob = $_POST['dob'];
	$formateddate = date('d-M-Y',strtotime($dob));
	$gender = $_POST['gender'];
	$status = $_POST['status'];
	$location = $_POST['location'];
	$visibility = $_POST['visibility'];
	$password = $_POST['password'];
	
	$query = "insert into MEMBERS (EMAIL_ID, F_NAME, SCREEN_NAME, DATE_OF_BIRTH, GENDER, STATUS, LOCATION, VISIBILITY_LEVEL, PASSWORD)
					values (:em, :fn, :sn, :dob, :g, :s, :l, :v, :p)";
	
	$stid = oci_parse($conn, $query);
	
	oci_bind_by_name($stid, ':em', $emailID);
	oci_bind_by_name($stid, ':fn', $fname);
	oci_bind_by_name($stid, ':sn', $sname);
	oci_bind_by_name($stid, ':dob', $formateddate);
	oci_bind_by_name($stid, ':g', $gender);
	oci_bind_by_name($stid, ':s', $status);
	oci_bind_by_name($stid, ':l', $location);
	oci_bind_by_name($stid, ':v', $visibility);
	oci_bind_by_name($stid, ':p', $password);
	
	$r = oci_execute($stid);  // executes and commits
	
	if ($r) {
		//echo "One row inserted";
		$_SESSION['sname'] = $sname;
		$_SESSION['loggedin'] = true;
		$_SESSION['email'] = $emailID;
		$_SESSION['sname'] = $sname;
		
		header('Location: fbhome.php?sname='.$_SESSION['sname']);
        //exit;
	}
	else {
		//echo "Not registered";
		header('Location: fbindex.php?erro=1');
		$err = oci_error($stid); 
        echo $err['message'];
	}
	
	oci_free_statement($stid);
	oci_close($conn);
}

?>