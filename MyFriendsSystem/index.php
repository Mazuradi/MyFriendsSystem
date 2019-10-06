<!-- Index.php -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="description" content="Assignment 2: My Friends System" />
  <meta name="keywords" content="PHP" />
  <meta name="author"   content="Brock Giller" />
  <link rel="stylesheet" type="text/css" href="style/style.css" />
  <title>Home: My Friends</title>
</head>
<body>
  <h1>- My Friend System -<br>Assignment Home Page</h1>
  <hr>
  <p>Name: Brock Giller</p>
  <p>Student ID: 101624498</p>
  <p>Email: 101624498@student.swin.edu.au</p>
  <br>
  <p>I declare that this assignment is my individual work. I have not worked collaboratively nor have<br> I
     copied from any other student’s work or from any other source.</p>
  <br>
  
  <?php
    //Creating the tables script
    require_once("settings.php");
   
    //Setting up the database and server.
    $dbConnect = @mysqli_connect(DB_HOST, DB_USER, DB_PSWD) 
      or die('Failed to connect to server.');
    @mysqli_select_db($dbConnect, DB_NAME)
	  or die('Database not available');
	  
	//Creating Tables if they do not exist.
	$tableFriendsCreate = "CREATE TABLE IF NOT EXISTS friends (
		                   friend_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
						   friend_email VARCHAR(50) NOT NULL,
						   password VARCHAR(20) NOT NULL,
						   profile_name VARCHAR(30) NOT NULL,
						   date_started DATE NOT NULL,
						   num_of_friends INT UNSIGNED)";
	$tableMyFriendsCreate = "CREATE TABLE IF NOT EXISTS myfriends (
		                     friend_id1 INT NOT NULL,
						     friend_id2 INT NOT NULL)";
    //Create the tables.
	mysqli_query($dbConnect, $tableFriendsCreate);
	mysqli_query($dbConnect, $tableMyFriendsCreate);
	
	//Populate tables with data.
	$currentdate = date("Y-m-d");
	$dataexists = "SELECT * FROM myfriends";
	$dbexists = "SELECT * FROM friends";
	$dbquery = mysqli_query($dbConnect, $dbexists);
	$dataquery = mysqli_query($dbConnect, $dataexists);
	if(mysqli_num_rows($dataquery) == 0 && mysqli_num_rows($dbquery) == 0) {
		$tablefriendspopulation = "INSERT INTO friends (friend_email, password, profile_name, date_started, num_of_friends)
								   VALUES ('lewish@gmail.com', 'mercedes44', 'Lewis', '$currentdate', '0'),
								          ('maxv@gmail.com', 'redbull3', 'Max', '$currentdate', '0'),
										  ('pierreg@gmail.com', 'rebull10', 'Pierre', '$currentdate', '0'),
										  ('charlesl@gmail.com', 'ferrari16', 'Charles', '$currentdate', '0'),
										  ('danielr@gmail.com', 'renault3', 'Daniel', '$currentdate', '0'),
										  ('sebastianv@gmail.com', 'ferrari5', 'Sebastian', '$currentdate', '0'),
										  ('landon@gmail.com', 'mclaren4', 'Lando', '$currentdate', '0'),
										  ('nicoh@gmail.com', 'renault27', 'Nico', '$currentdate', '0'),
										  ('kimir@gmail.com', 'alfaromeo7', 'Kimi', '$currentdate', '0'),
										  ('valterrib@gmail.com', 'mercedes77', 'Valterri', '$currentdate', '0');";
		$tablemyfriendspopulation = "INSERT INTO myfriends (friend_id1, friend_id2)
		                             VALUES (1,5),(1,3),(2,7),(2,1),(3,6),(3,4),(4,2),(4,7),(5,8),(5,9),(6,8),(6,3),(7,6),(7,5),(8,3),(8,9),(9,1),(9,4),(10,7),(10,4);";
		mysqli_query($dbConnect, $tablefriendspopulation);
		mysqli_query($dbConnect, $tablemyfriendspopulation);
		echo "<p>Tables successfully created and populated.</p>";
	}
	else {
		echo "<p>Tables and data already exist in database.</p>";
	}
	
	//Closing the connection to server.
	mysqli_close($dbConnect);
  ?>
  
  <a href="signup.php">Sign-Up</a>&nbsp &nbsp
  <a href="login.php">Login</a>&nbsp &nbsp
  <a href="about.php">About</a>
</body>
</html>
