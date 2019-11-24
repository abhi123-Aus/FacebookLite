<?php

require_once('db.class.php');

// Resume existing session
session_start();

$objDb = new db();
$conn = $objDb -> connect_orcale();

$email_id = $_SESSION['email_validated'];
$sname = $_SESSION['user_validated'];

if (isset($_POST['sname']))
{
                    $sname = $_POST['sname']; 			
                    $status = $_POST['status'];					
                    $location = $_POST['location'];
                    $visibility = $_POST['visibility'];
					$password = $_POST['password'];
					
				 
					$query = "UPDATE members SET SCREEN_NAME = :sn,STATUS = :s ,LOCATION =:l ,VISIBILITY_LEVEL = :v , PASSWORD =:p WHERE EMAIL_ID = '".$_SESSION['email']."'";
					
								
					$stid = oci_parse($conn,$query);			
                    
         		 
		            oci_bind_by_name($stid, ':sn', $sname);				
					oci_bind_by_name($stid, ':s', $status);
					oci_bind_by_name($stid, ':l', $location);
					oci_bind_by_name($stid, ':v', $visibility);
					oci_bind_by_name($stid, ':p', $password);

                    $r = oci_execute($stid);  // executes and commits
					
					if ($r) 
					{
						header('Location: fbprofile.php?sname='.$_SESSION['user_validated']);
						echo "Updated";
						exit;
					}
		            else 
					{						
			          echo "Not updated";
					}
									
					$query1 = "SELECT F_NAME from members WHERE EMAIL_ID = '".$_SESSION['email']."'";
					$stid1 = oci_parse($conn,$query1);
					
					oci_execute($stid1);
					
		           oci_free_statement($stid);
  				   oci_close($conn);
}

?>