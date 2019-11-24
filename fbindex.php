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
        <a class="navbar-brand" href="fbindex.php">FacebookLite</a>
      </div>
    </div>
  </nav>
  <!-- ./nav -->

  <!-- main -->
  <main class="container">
  <h1 class="text-center">Welcome to FacebookLite <br></small></h1>
    <div class="row">
      <div class="col-md-6">
        <h4>Please fill in your credentials to login.</h4>
        <!-- login form -->
        <form method="post" action="fblogin.php">
		  <div class="form-group <?php echo (!empty($errormsg)) ? 'has-error' : ''; ?>">
            <input class="form-control" type="text" name="email" placeholder="Email ID">
          </div>
          <div class="form-group <?php echo (!empty($errormsg)) ? 'has-error' : ''; ?>">
            <input class="form-control" type="password" name="password" placeholder="Password">
          </div>
          <div class="form-group">
            <input class="btn btn-primary" type="submit" name="login" value="Login">
          </div>
        </form>
        <!-- ./login form -->
      </div>
      <div class="col-md-6">
        <h4>Don't have an account yet? Sign Up!</h4>
        <!-- signup form -->
        <form method="post" action="fbsignup.php">
          <div class="form-group">
            <input class="form-control" type="text" name="fname" placeholder="Fullname">
          </div>
          <div class="form-group">
            <input class="form-control" type="text" name="sname" placeholder="Screenname">
          </div>
          <div class="form-group">
            <input class="form-control" type="text" name="email" placeholder="Email ID">
          </div>
		  <div class="form-group">
            <input class="form-control" type="text" name="dob" placeholder="Date of Birth(DD-MM-YYYY)">
          </div>
		  <div class="form-group">
            <input class="form-control" type="text" name="gender" placeholder="Gender">
          </div>
		  <div class="form-group">
            <input class="form-control" type="text" name="status" placeholder="Status">
          </div>
		  <div class="form-group">
            <input class="form-control" type="text" name="location" placeholder="Location">
          </div>
		  <div class="form-group">
            <input class="form-control" type="text" name="visibility" placeholder="Visibility Level">
          </div>
          <div class="form-group">
            <input class="form-control" type="password" name="password" placeholder="Password">
          </div>
          <div class="form-group">
            <input class="btn btn-success" type="submit" name="signup" value="Sign Up">
          </div>
        </form>
        <!-- ./signup form -->
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
  <script type="text/javascript" src="bootstrap.min.js"></script>
  <script type="text/javascript" src="script.js"></script>
</body>
</html>