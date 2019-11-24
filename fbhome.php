<?php

require_once('db.class.php');

// Resume existing session
session_start();

$objDb = new db();
$conn = $objDb -> connect_orcale();

if (!isset($_SESSION['email'])) {
	header('Location: fbindex.php?erro=1');
}

$_SESSION['feed'] = $_GET['sname'];
$_SESSION['user_validated'] = $_SESSION['sname'];
$_SESSION['email_validated'] = $_SESSION['email'];

?>

<!DOCTYPE html>
<html>
<head>
  <title>FacebookLite</title>
  <link rel="stylesheet" type="text/css" href="bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <!-- nav -->
  <nav class="navbar navbar-default">
    <div class="container-fluid">
	  <div class="navbar-header">
	    <div class="navbar-brand">
		  <a class="navbar-brand" href="fbindex.php">FacebookLite</a>
		</div>
	  </div>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="fbregister-group.php">Edit group</a></li>
        <li><a href="fbgroup.php">Group</a></li>
        <li><a <?php echo 'href="fbhome.php?sname='.$_SESSION['user_validated'].'"';?>>Home</a></li>
		<li><a <?php echo 'href="fbprofile.php?sname='.$_SESSION['user_validated'].'"';?>>Profile</a></li>
        <li><a href="fblogout.php">Logout</a></li>
      </ul>
    </div>
  </nav>
  <!-- ./nav -->

  <!-- main -->
  <main class="container">
    <div class="row">
      <div class="col-md-3">
        <!-- profile brief -->
        <div class="panel panel-default">
          <div class="panel-body">
            <h4><?= $_SESSION['feed'] ?></h4>
            <p>My FacebookLite Code!</p>
          </div>
        </div>
        <!-- ./profile brief -->

        <!-- friend requests -->
        <div class="panel panel-default">
          <div class="panel-body">
            <h4>Friend Requests</h4>
            <?php
            $email_id = $_SESSION['email_validated'];
            $sql_query1 = "select * from FRIENDSHIPREQUEST where REQUESTSTATUS = 'Pending' and MEMBERS_EMAIL_ID = '".$email_id."' order by MEMBERS_EMAIL_ID1";
			$stid1 = oci_parse($conn, $sql_query1);
			$result1 = oci_execute($stid1);
			  
            if ($result1) {
				?><ul><?php
				$rows1 = oci_fetch_all($stid1, $array1);
				
				if ($rows1 != 0) {
					for ($i = 0; $i < $rows1; $i++) {
						$sql_query2 = "select * from MEMBERS where EMAIL_ID = '".$array1['MEMBERS_EMAIL_ID1'][$i]."'";
						$stid2 = oci_parse($conn, $sql_query2);
						$result2 = oci_execute($stid2);
						$friend_request = oci_fetch_array($stid2, OCI_BOTH);
						?>
						<li>
						  <a href="#"><?php echo $friend_request['F_NAME']; ?></a>
						  <br>
						  <a class="text-success" <?php echo 'href="friend-request.php?sname='.$friend_request['SCREEN_NAME'].'&log=accept"'; ?>>[accept]</a>
						  <a class="text-danger" <?php echo 'href="friend-request.php?sname='.$friend_request['SCREEN_NAME'].'&log=decline"'; ?>>[decline]</a>
						</li>
						<?php
					}
				} else {
					?></ul><p class="text-center">No friend requests yet!</p><?php
				}
            } else {
				$errormsg = oci_error($stid1); 
				echo $errormsg['message'];
				echo 'Error executing query, please contact your site administrator';
            }
			?>
          </div>
        </div>
        <!-- ./friend requests -->
      </div>
      <div class="col-md-6">
        <!-- post form -->
        <form method="post" action="create-post.php">
          <div class="input-group">
            <input class="form-control" type="text" name="content" placeholder="What's on your mind?...">
            <span class="input-group-btn">
              <button class="btn btn-success" type="submit" name="post">Post</button>
            </span>
          </div>
        </form><hr>
        <!-- ./post form -->
        <!-- feed -->
        <div>
          <!-- post -->
          <?php
          $email_id = $_SESSION['email_validated'];
		  $sql_query1 = "select * from FRIENDSHIP where MEMBERS_EMAIL_ID = '".$email_id."'";
		  $stid1 = oci_parse($conn, $sql_query1);
		  $result1 = oci_execute($stid1);

          if ($result1) {
			  $rows1 = oci_fetch_all($stid1, $array1);
			  
			  if ($rows1 != 0) {
				  // Get all parent posts
				  $sql_query2 = "select PARENTPOSTID from (select PARENTPOSTID, max(POSTTIME_STAMP) as LATEST_POSTTIME_STAMP from POST where MEMBERS_EMAIL_ID in ('".$email_id."'";
				  for ($i = 0; $i < $rows1; $i++) {
					  $sql_query2 .= ", '".$array1['MEMBERS_EMAIL_ID1'][$i]."'";
				  }
				  $sql_query2 .= ") group by PARENTPOSTID) order by LATEST_POSTTIME_STAMP desc";
				  
				  $stid2 = oci_parse($conn, $sql_query2);
				  $result2 = oci_execute($stid2);
				  
				  if ($result2) {
					  $rows2 = oci_fetch_all($stid2, $array2);
					  
					  if ($rows2 != 0) {
						  for ($i = 0; $i < $rows2; $i++) {
							  // Get parent post
							  $sql_query3 = "select * from POST where POSTID = '".$array2['PARENTPOSTID'][$i]."' and POST_POSTID = '".$array2['PARENTPOSTID'][$i]."'";
							  $stid3 = oci_parse($conn, $sql_query3);
							  $result3 = oci_execute($stid3);
							  $parent_post = oci_fetch_array($stid3, OCI_BOTH);
							  ?>
							  <div class="panel panel-default">
								<div class="panel-body">
								  <p><?php echo $parent_post['BODY']->load(); ?></p>
								  <hr>
								  <div> <!--DIV PRINCIPAL COMMENT-->
									<form method="post" action="comment.php">
									  <div col-md-2 class="input-group"> <!--Comment-->
									    <input type="text" placeholder="Write a comment..." name="comment" class="form-control">
									    <input type="hidden" name="parentpostid" value="<?php echo $parent_post['PARENTPOSTID']; ?>">
									    <span class="input-group-btn">
										  <button type="submit" class="btn btn-default" id="button_comment">Comment</button>
									    </span>
									  </div>
									</form>
									<hr>
									<div col-md-2>
									  <ul class="list-group">
									  <!--Comment-->
									  <?php
									  $sql_query4 = "select * from POST where POSTID != '".$parent_post['PARENTPOSTID']."' and POST_POSTID = '".$parent_post['PARENTPOSTID']."' order by POSTID";
									  $stid4 = oci_parse($conn, $sql_query4);
									  $result4 = oci_execute($stid4);
									  
									  while($comment = oci_fetch_array($stid4, OCI_BOTH)) {
										  ?>
										  <li class="list-group-item">
										    <div col-md-2 >
											  <label><?php echo $comment['MEMBERS_EMAIL_ID']; ?></label><br> <!--Name of the commenting user-->
											  <div style="margin-left: 22px;"><?php echo $comment['BODY']->load(); ?></div>
											</div>
											<hr>
											<!--Comment reply-->
											<?php
											$sql_query5 = "select * from POST where POST_POSTID = '".$comment['POSTID']."' and PARENTPOSTID = '".$comment['PARENTPOSTID']."' order by POSTID";
											$stid5 = oci_parse($conn, $sql_query5);
											$result5 = oci_execute($stid5);
											
											while($reply = oci_fetch_array($stid5, OCI_BOTH)) {
												?>
												<div col-md-2 style="margin-left: 40px;">
												  <label><?php echo $reply['MEMBERS_EMAIL_ID']; ?></label><br> <!--Name of the user who answered the comment-->
												  <div style="margin-left: 19px;"><?php echo $reply['BODY']->load(); ?></div>
												</div>
												<hr>
												<?php
											}
											?>
											<!--Input for comment response-->
											<div col-md-2> <!--Comment reply-->
											  <form method="post" action="reply.php">
											    <div col-md-2 class="input-group"> <!--Button inside the text-->
											      <input type="text" placeholder="Enter a reply..." name="reply" class="form-control">
												  <input type="hidden" name="parentpostid" value="<?php echo $comment['PARENTPOSTID']; ?>">
												  <input type="hidden" name="postpostid" value="<?php echo $comment['POSTID']; ?>">
												  <span class="input-group-btn">
												    <button type="submit" class="btn btn-default" id="button_comment_resp">Reply</button>
												  </span>
											    </div>
											  </form>
											</div>
										  </li>
										
									  <!--end comment without list-group-->
									  <?php
									  }
									  ?>
									  </ul>
									</div>
								  </div><!--FINAL DIV PRINCIPAL COMMENT-->
								</div>
							    <div class="panel-footer">
								  <span>posted <?php echo $parent_post['POSTTIME_STAMP'] ?> by <?php echo $parent_post['MEMBERS_EMAIL_ID']; ?></span>
								  <!--<span class="pull-right"><a class="text-danger" href="#">[delete]</a></span>-->
								</div>
							  </div>
							<?php
						  }
					  }
				  }
				  //$post = oci_fetch_array($stid1, OCI_BOTH);
			  } else {
				  ?>
				  <p class="text-center">No posts yet!</p>
				  <?php
			  }
		  } else {
			  $errormsg = oci_error($stid);
			  echo $errormsg['message'];
			  echo 'Error executing query, please contact your site administrator';
		  }
		  ?>
          <!-- ./post -->
        </div>
        <!-- ./feed -->
      </div>
      <div class="col-md-3">
      <!-- add friend -->
        <div class="panel panel-default">
          <div class="panel-body">
            <h4>Add Friend</h4>
            <?php
			$email_id = $_SESSION['email_validated'];
            $sql_query1 = "select * from FRIENDSHIP where MEMBERS_EMAIL_ID = '".$email_id."' order by MEMBERS_EMAIL_ID1";
			$stid1 = oci_parse($conn, $sql_query1);
			$result1 = oci_execute($stid1);
			
			$sql_query2 = "select * from FRIENDSHIPREQUEST where MEMBERS_EMAIL_ID = '".$email_id."' order by MEMBERS_EMAIL_ID1";
			$stid2 = oci_parse($conn, $sql_query2);
			$result2 = oci_execute($stid2);
			
			$sql_query3 = "select * from FRIENDSHIPREQUEST where MEMBERS_EMAIL_ID1 = '".$email_id."' order by MEMBERS_EMAIL_ID";
			$stid3 = oci_parse($conn, $sql_query3);
			$result3 = oci_execute($stid3);
			
			if ($result1 && $result2 && $result3) {
				?><ul><?php
				$rows1 = oci_fetch_all($stid1, $array1);
				$rows2 = oci_fetch_all($stid2, $array2);
				$rows3 = oci_fetch_all($stid3, $array3);
				
				if ($rows1 != 0 || $rows2 != 0 || $rows3 != 0) {
					$sql_query4 = "select * from MEMBERS where EMAIL_ID not in ('".$email_id."'";
					
					if ($rows1 != 0) {
						for ($i = 0; $i < $rows1; $i++) {
							$sql_query4 .= ", '".$array1['MEMBERS_EMAIL_ID1'][$i]."'";
						}
					}
					
					if ($rows2 != 0) {
						for ($i = 0; $i < $rows2; $i++) {
							$sql_query4 .= ", '".$array2['MEMBERS_EMAIL_ID1'][$i]."'";
						}
					}
					
					if ($rows3 != 0) {
						for ($i = 0; $i < $rows3; $i++) {
							$sql_query4 .= ", '".$array3['MEMBERS_EMAIL_ID'][$i]."'";
						}
					}
					
					$sql_query4 .= ") order by EMAIL_ID";
				} else {
					$sql_query4 = "select * from MEMBERS where EMAIL_ID != '".$email_id."' order by EMAIL_ID";
				}
				
				$stid4 = oci_parse($conn, $sql_query4);
				$result4 = oci_execute($stid4);
				
				if ($result4) {
					while($add_friend = oci_fetch_array($stid4, OCI_BOTH)) {
						?>
						<li>
						  <a <?php echo 'href="fbprofile.php?sname='.$add_friend['SCREEN_NAME'].'';?>">
						    <?php echo $add_friend['F_NAME']; ?>
						  </a>
						  <a <?php echo 'href="add-friend.php?sname='.$add_friend['SCREEN_NAME'].''; ?>">[add]</a>
						</li>
						<?php
					}
				} else {
					?></ul><p class="text-center">No users to add!</p><?php
				}
            } else {
				$errormsg = oci_error($stid1); 
				echo $errormsg['message'];
				echo 'Error executing query, please contact your site administrator';
            }
          ?>
          </div>
        </div>
        <!-- ./add friend -->

        <!-- friends -->
        <div class="panel panel-default">
          <div class="panel-body">
            <h4>Friends</h4>
            <?php
			$email_id = $_SESSION['email_validated'];
            $sql_query1 = "select * from FRIENDSHIP where MEMBERS_EMAIL_ID = '".$email_id."' order by MEMBERS_EMAIL_ID1";
			$stid1 = oci_parse($conn, $sql_query1);
			$result1 = oci_execute($stid1);
			
			if ($result1) {
				?><ul><?php
				$rows1 = oci_fetch_all($stid1, $array1);
				
				if ($rows1 != 0) {
					for ($i = 0; $i < $rows1; $i++) {
						$sql_query2 = "select * from MEMBERS where EMAIL_ID = '".$array1['MEMBERS_EMAIL_ID1'][$i]."'";
						$stid2 = oci_parse($conn, $sql_query2);
						$result2 = oci_execute($stid2);
						$friend = oci_fetch_array($stid2, OCI_BOTH);
						?>
						<li>
						  <a <?php echo 'href="fbprofile.php?sname='.$friend['SCREEN_NAME'].'';?>">
						    <?php echo $friend['F_NAME']; ?>
						  </a>
						  <a class="text-danger" <?php echo 'href="unfriend.php?sname='.$friend['SCREEN_NAME'].'"';?>">[unfriend]</a>
						</li>
						<?php
					}
				} else {
					?></ul><p class="text-center">No friends yet!</p><?php
				}
            } else {
				$errormsg = oci_error($stid1); 
				echo $errormsg['message'];
				echo 'Error executing query, please contact your site administrator';
            }
          ?>
          </div>
        </div>
        <!-- ./friends -->
      </div>
    </div>
  </main>
  <!-- ./main -->

  <!-- footer -->
  <footer class="container text-center">
    <ul class="nav nav-pills pull-right">
      <li>FacebookLite - Made by [Abhinav Gupta]</li>
    </ul>
  </footer>
  <!-- ./footer -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/script.js"></script>
</body>
</html>