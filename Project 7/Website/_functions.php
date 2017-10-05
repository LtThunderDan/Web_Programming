<?php
$G_logs_users_types = array(
  "-1" => "User Account Deleted",
  "0"  => "User Account Created",
  "1"  => "User Logged In",
  "2"  => "User Logged Out",
  "3"  => "User Changed Password"
);

//lets create a user session.
if(!isset($_SESSION)){
    session_start();
  }

  //connects you to the server. Mucho important.
  function connect(){
    $GLOBALS['link'] = mysqli_connect("tund.cefns.nau.edu", "daw277script", "qZ6xCYePP6Awn9yz", "daw277") or die("MySQL Error: Failed to connect.");
  }

  function protect($string){
    return strip_tags(addslashes($string));
  }

  //finds out if the user is logged in or not.
  function authCheck(){
    if(!isset($_SESSION['uid'])){
      return FALSE;
    } else{
      return TRUE;
    }
  }

  //function used to get users info so we can print it out.
  function getUserInfo(){
    $user_query = "SELECT `email`, `username` FROM `users` WHERE `userID`='".$_SESSION['uid']."'";
    $user_get = mysqli_query($GLOBALS['link'], $user_query) or die("MySQL Error: Failed to get user info.");
    $_SESSION['user'] = mysqli_fetch_assoc($user_get);
  }

  //function used to get users info so we can print it out.
  function getUserLogs(){
    $log_query = "SELECT `logID`, `type`, `date` FROM `logs_users` WHERE `userID`='".$_SESSION['uid']."' ORDER BY `logID` DESC";
    $log_get = mysqli_query($GLOBALS['link'], $log_query) or die("MySQL Error: Failed to get user logs.");
    return $log_get;
  }

  //lets encrypt the password. for fun.
  function hashPassword($pPassword, $salt="##WHatIsuP?!*", $pepper="1N0tMuCH#@0") {
    return sha1(md5($salt . $pPassword . $pepper));
  }

?>
