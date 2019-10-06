<?php
  //Session Details.
  session_start();
  if(!isset($_SESSION["loggedin"])) {
	  $_SESSION["loggedin"] = false;
	  $_SESSION["profname"] = "";
	  $_SESSION["frid"] = "";
  }
  
  $loggedinID = $_SESSION["frid"];
  $savedProfilename = $_SESSION["profname"];
  $loggedinStatus = $_SESSION["loggedin"];
  
  //Testing purpose.
  $status = 0;
  if($loggedinStatus == true) {$status = 1;}
  
  echo "The logged in status is: " . $status . " The ID is: " . $loggedinID . " The prof name is: " . $savedProfilename;
		
  //Connect to db, error handling.
  require_once("settings.php");
  $dbConnect = @mysqli_connect(DB_HOST, DB_USER, DB_PSWD)
	or die("Failed to connect to the server!");
  @mysqli_select_db($dbConnect, DB_NAME)
	or die("Database not available!");
  
  //Remove Script, unbind a friend from the current user.
  $friend = $_GET["friendid"];
  $query = "DELETE FROM myfriends WHERE friend_id1 = $loggedinID and friend_id2 = $friend";
  mysqli_query($dbConnect, $query);
  
  //Close connection and release results.
  mysqli_close($dbConnect);
  header("location:friendlist.php");
?>