<!-- Login.php -->

<?php
  //Session Details
  session_start();
  if(!isset($_SESSION["loggedin"])) {
	  $_SESSION["loggedin"] = false;
	  $_SESSION["profname"] = "";
	  $_SESSION["frid"] = "";
  }
  
  $loggedinID = $_SESSION["frid"];
  $savedProfilename = $_SESSION["profname"];
  $loggedinStatus = $_SESSION["loggedin"];
  
  //Connect to db, error handling.
  require_once("settings.php");
  $dbConnect = @mysqli_connect(DB_HOST, DB_USER, DB_PSWD)
	or die("Failed to connect to the server!");
  @mysqli_select_db($dbConnect, DB_NAME)
	or die("Database not available!");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="description" content="Assignment 2: My Friends System" />
  <meta name="keywords" content="HTML, PHP" />
  <meta name="author" content="Brock Giller" />
  <link rel="stylesheet" type="text/css" href="style/formstyle.css" />
  <title>Login: My Friends</title>  
</head>

<body>
  <h1>- My Friend System -<br>LogIn Page</h1>
  <hr>
  <div id="div-formposition">
  <form action="login.php" method="POST">
    Email: <input type="email" name="email" class="moveinputbox" value="<?php if(isset($_POST["email"])){echo $_POST["email"];} ?>" required /><br>
	Password: <input type="password" id="loginpswd" name="pswd" required /><br>
	<input type="checkbox" id="showpswd" onclick="showPswd()">Show Password<br>
		  
	<input type="submit" class="login" value="Log-In" />
	<input type="reset" class="clear" value="Clear" />
  </form>
  
  <!--JavaScript function for showing pswd-->
  <script language="JavaScript">
     function showPswd() {
		 var x = document.getElementById("loginpswd");
		 if(x.type === "password") {
			 x.type = "text";
		 }
		 else {
			 x.type = "password";
		 }
	 }
   </script>
  
  <?php
   //Add to validate view from db to continue.
   if(isset($_POST["email"]) && isset($_POST["pswd"]) && $_POST["email"] != "" && $_POST["pswd"] != "") {
	   
	   //Variables.
	   $email = $_POST["email"]; $pswd = $_POST["pswd"];
	   $email_err = $emailexist_err = $pswd_err = "";
	   $valid = 0;
	   
	   //Validation of email.
	   if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
		   $result = mysqli_query($dbConnect, "SELECT friend_email FROM friends WHERE friend_email = '$email'");
			if(mysqli_num_rows($result) == 1) {
				$valid++;
			}
			else {
				$emailexist_err = "Email does not exist in the database.";
			}
	   }
	   else {
		   $email_err = "The email entered is not of the correct format.";
	   }
	   
	   //Validation of password.
	   if(ctype_alnum($pswd)) {
		   $valid++;
	   }
	   else {
		   $pswd_err = "Password does not match required format";
	   }
	   
	   //If valid login.
	   if($valid === 2) {
		   //To login in to friends site.
		   $loginquery = "SELECT friend_id, profile_name FROM friends WHERE friend_email = '$email' and password = '$pswd'";
		   $result = mysqli_query($dbConnect, $loginquery);
		   $userdetails = mysqli_fetch_row($result);
		   if(mysqli_num_rows($result) == 1) {
			   //User can login.
			   $_SESSION["loggedin"] = true;
			   $_SESSION["profname"] = $userdetails[1];
			   $_SESSION["frid"] = $userdetails[0];
			   header("location:friendlist.php");
		   }
		   else {
			   //User can't log in.
			   echo "<p>Log In failed.</p>";
		   }
	   }
	   else {
		   //Show error statements.
		   echo "<p>{$email_err}</p>";
		   echo "<p>{$emailexist_err}</p>";
		   echo "<p>{$pswd_err}</p>";
	   }
   }
   else	{
	   //Error inputs not set.
	   echo "<p>Please enter an email address and password.</p>";
   }
  ?>
  </div>
  <a href="index.php" class="homelink">Return Home</a>
</body>
</html>