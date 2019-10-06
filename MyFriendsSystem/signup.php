<!-- SignUp.php -->

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
  <title>Sign-Up: My Friends</title>  
</head>

<body>
  <h1>- My Friend System -<br>Registration Page</h1>
  <hr>
  <div id="div-formposition">
  <form action="signup.php" method="POST">
    Email: <input type="email" name="email" class="emailmove" value="<?php if(isset($_POST["email"])){echo $_POST["email"];} ?>" required /><br>
	Profile Name: <input type="text" name="profname" class="profnamemove" value="<?php if(isset($_POST["profname"])){echo $_POST["profname"];} ?>" required /><br>
	Password: <input type="password" id="loginpswd" class="pswdmove" name="pswd" /><br>
	Confirm Password: <input type="password" id="confloginpswd" name="confirmpswd" /><br>
	<input type="checkbox" id="showpswd" onclick="showPswd()">Show Password <br>
		  
	<input type="submit" class="login" name="submit" value="Register" />
	<input type="reset" class="clear" value="Clear" />
  </form>
  
  <!--JavaScript function for showing pswd-->
  <script language="JavaScript">
     function showPswd() {
		 var x = document.getElementById("loginpswd");
		 var y = document.getElementById("confloginpswd");
		 if(x.type === "password" && y.type === "password") {
			 x.type = "text";
			 y.type = "text";
		 }
		 else {
			 x.type = "password";
			 y.type = "password";
		 }
	 }
   </script>
  
  <?php
   //Add to validate and add to database.
   if(isset($_POST["email"]) && isset($_POST["profname"]) && isset($_POST["pswd"]) && isset($_POST["confirmpswd"]) && isset($_POST["submit"])
	   && $_POST["email"] && $_POST["profname"] && $_POST["pswd"] && $_POST["confirmpswd"]) {
	   
	   //Variables.
	   $email = $_POST["email"]; $profname = $_POST["profname"];
	   $pswd = $_POST["pswd"]; $confpswd = $_POST["confirmpswd"];
	   $valid = 0;
	   $profname_err = $pswdformat_err = $pswdmatch_err = $emailformat_err = $emailexists_err = "";
	   
	   //Check profile name.
       if(ctype_alpha($profname)) {
		   $valid++;
	   }
	   else {
		   $profname_err = "Profile Name is not of a valid format.";
	   }
	   //Check Passwords.
	   if(ctype_alnum($pswd) && ctype_alnum($confpswd)) {
		   if(strcmp($pswd, $confpswd) == 0) {
			   $valid++;
		   }
		   else {
			   $pswdmatch_err = "Passwords do not match.";
		   }
	   }
	   else {
		   $pswdformat_err = "Passwords are not of the specified type (letters and numbers only).";
	   }
	   
	   //Check email.
	   if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$result = mysqli_query($dbConnect, "SELECT friend_email FROM friends WHERE friend_email = '$email'");
			if(mysqli_num_rows($result) == 0) {
				$valid++;
			}
			else {
				$emailexists_err = "Email already exists within our database.";
			}
	   }
	   else {
		   $emailformat_err = "Email does not match a standard format.";
	   }
	   
	   //All Validated, enter in db, login and redirect.
	   if($valid === 3) {
		   $currentdate = date("Y-m-d");
		   $addUserDetails = "INSERT INTO friends (friend_email, password, profile_name, date_started, num_of_friends)
							  VALUES ('$email', '$pswd', '$profname', '$currentdate', '0')";
		   mysqli_query($dbConnect, $addUserDetails);
           $idquery = mysqli_query($dbConnect, "SELECT friend_id FROM friends WHERE friend_email = '$email'");
           $userid = mysqli_fetch_row($idquery);
           $_SESSION["frid"] = $userid[0];
		   $_SESSION["loggedin"] = true;
		   $_SESSION["profname"] = $profname;
		   header("location:friendadd.php");
	   }
	   else {
			echo "<p> {$profname_err} <br> {$pswdformat_err} <br> {$pswdmatch_err} <br> {$emailexists_err} <br> {$emailformat_err} </p>";
	   }
   }
   else	{
	   echo "<p>Please ensure you enter all the data from the form: <br>Email, Profile Name, Password, Password Confirmation.</p>";
   }
  ?>
  </div>
  <a href="index.php" class="homelink">Return Home</a>
</body>
</html>