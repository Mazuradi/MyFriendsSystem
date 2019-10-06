<!-- LogOut.php -->

<?php
  //Initializing the session.
  session_start();
  
  //Unset session variables and destroy session.
  session_unset();
  session_destroy();
  
  //Redirect to required page.
  header("location: index.php");
?>