<?php
  require_once("_functions.php");
  connect();

  //if there is a user, log their log out time.
  if(isset($_SESSION['uid'])){
    $log_UserStr = "INSERT INTO `logs_users` (`type`,`date`,`userID`) ";
    $log_UserStr .= "VALUES ('2', '" . date("Y-m-d H:i:s") . "', '".$_SESSION['uid']."')";
    $log_User = mysqli_query($GLOBALS['link'], $log_UserStr) or die("Failed to create user log");
  }

  //terminate/ destory user session. Then make a new one.
  session_destroy();
  session_start();

  //send them back to index page.
  header("Location: /~daw277/index.php");
?>
