<!-- FriendList.php -->

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
  
  if($loggedinStatus == false){
	  header("location:login.php");
  }
  
  //Connect to db, error handling.
  require_once("settings.php");
  $dbConnect = @mysqli_connect(DB_HOST, DB_USER, DB_PSWD)
	or die("Failed to connect to the server!");
  @mysqli_select_db($dbConnect, DB_NAME)
	or die("Database not available!");
	
  //To calculate # of friends of user
  $numoffriends = "SELECT COUNT(*) FROM myfriends WHERE friend_id1 = $loggedinID";
  $num = mysqli_query($dbConnect, $numoffriends);
  $numfriends = mysqli_fetch_row($num);
  $updatenum = "UPDATE friends SET num_of_friends = $numfriends[0] WHERE friend_id = $loggedinID";
  mysqli_query($dbConnect, $updatenum);
	
  //Get the user's number of friends.
  $numquery = mysqli_query($dbConnect, "SELECT num_of_friends FROM friends WHERE friend_id = $loggedinID");
  $usersnumoffriends = mysqli_fetch_row($numquery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="description" content="Assignment 2: My Friends System" />
  <meta name="keywords" content="HTML, PHP" />
  <meta name="author" content="Brock Giller" />
  <link rel="stylesheet" type="text/css" href="style/friendsstyle.css" />
  <title>Friends List: My Friends</title>  
</head>

<body>
  <h1>Friend's List Page: <?php $pname = $_SESSION["profname"]; echo $_SESSION["profname"] ?><br> Number of Friends: <?php echo $usersnumoffriends[0] ?></h1>
  <hr>
  <h3 class="subheading">Current Friends</h3>
  <?php
	
	//Query for getting the user's friends.
	$friendsUltraquery = "SELECT friend_id, profile_name FROM friends WHERE friend_id IN (SELECT friend_id2 FROM myfriends WHERE friend_id1 = $loggedinID) ORDER BY profile_name";
	$friends_num = mysqli_query($dbConnect, $friendsUltraquery);
	$friend = mysqli_fetch_row($friends_num);
	echo "<table>";
	echo "<tr><th>Friend's Name</th><th>Unfriend</th></tr>";
	while($friend) {
		echo "<tr><td>{$friend[1]}</td>";
		echo "<td><a href='unfriend.php?friendid=$friend[0]' class='button'>UNFRIEND</a></td></tr>";
		$friend = mysqli_fetch_row($friends_num);
	}
	echo "</table><br>";
	
	//Close connection and release results.
	mysqli_close($dbConnect);
  ?>
  <div	class="links">
  <a href="friendadd.php" class="linkspacing link">Add Friend's</a>
  <a href="logout.php" class="link">Log Out</a>
  </div>
</body>
</html>

