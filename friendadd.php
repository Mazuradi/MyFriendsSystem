<!-- FriendAdd.php -->

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
  <title>Friends Add: My Friends</title>  
</head>

<body>
  <h1>Friend's Add Page: <?php echo $_SESSION["profname"] ?><br> Number of Friends: <?php echo $usersnumoffriends[0]; ?></h1>
  <hr>
  <h3 class="subheading">Friends to Add</h3>
  
  <?php
    //Variables
	$offset = 0; $limit = 5;
	
	//Pagination Setup and page checking.
	$pag = "SELECT * FROM friends WHERE friend_id NOT IN (SELECT friend_id2 FROM myfriends WHERE friend_id1 = $loggedinID) and friend_id != $loggedinID";
	$pagquery = mysqli_query($dbConnect, $pag);
	$num_of_results = mysqli_num_rows($pagquery);
	$num_of_pages = ceil($num_of_results/$limit);
	if(!isset($_GET["page"])) {
		$page = 1;
	}
	else {
		$page = $_GET["page"];
	}
	$pageminus = $page -1;
	$pageplus = $page + 1;
	$offset = ($page-1)*$limit;
	
	//Check user's not currently a friend not including current user.
	$addquery = "SELECT friend_id, profile_name FROM friends WHERE friend_id NOT IN (SELECT friend_id2 FROM myfriends WHERE friend_id1 = $loggedinID) and friend_id != $loggedinID ORDER BY profile_name LIMIT $limit OFFSET $offset";
	$availableresult = mysqli_query($dbConnect, $addquery);
	$availpeople = mysqli_fetch_row($availableresult);
	
	echo "<table width='50%' border='1'>";
	echo "<tr><th>User's Name</th><th>Mutual Friends</th><th>Add User</th></tr>";
	while($availpeople) {
		//Mutual friends query.
		$mutualquery = "SELECT COUNT(friend_id2) FROM myfriends WHERE friend_id2 IN (SELECT friend_id2 FROM myfriends WHERE friend_id1 = $loggedinID) AND friend_id1 = $availpeople[0]";
		$mutualresult = mysqli_query($dbConnect, $mutualquery);
		$mutualfriends = mysqli_fetch_row($mutualresult);
		echo "<tr><td>{$availpeople[1]}</td>";
		echo "<td>{$mutualfriends[0]}</td>";
		echo "<td><a href='addfriend.php?friendid=$availpeople[0]' class='button'>Add as friend</a></td></tr>";
		$availpeople = mysqli_fetch_row($availableresult);
	}
	echo "</table><br>";
	//Pagination, checking for Prev and Next links.
	echo "<div class='links'>";
	if($page != 1) {
		echo "<a href='friendadd.php?page=$pageminus' class='linkspacing link'>Prev</a>";
	}
	if($page != $num_of_pages) {
		echo "<a href='friendadd.php?page=$pageplus' class='nextspacing link'>Next</a>";
	}
	echo "</div>";
	
	//Close connection and release results.
	mysqli_close($dbConnect);
  ?>
  <div class="links">
  <a href="friendlist.php" class="linkspacing link">Friend List</a>
  <a href="logout.php" class="link">Log Out</a>
  </div>
</body>
</html>