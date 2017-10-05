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

//lets grab the user name.
function getUsername($id){
    $user_query = "SELECT `username` FROM `users` WHERE `userID`='".$id."'";
    $user_get = mysqli_query($GLOBALS['link'], $user_query) or die("MySQL Error: Failed to get user info.");
    $user = mysqli_fetch_assoc($user_get) or die("MySQL Error: Failed to convert username.");
    return $user['username'];
  }

//grabs messages that were sent to the user.
function getMessages(){
  $msg_query = "(SELECT `messageID`,`date`,`subject`,`message`,`status_to` as `status`,'0' as `isSent`,`userID_from` as `userID` FROM `messages` WHERE `userID_to`='" . $_SESSION['uid'] . "' AND `status_to` != '-1') UNION ALL (SELECT `messageID`,`date`,`subject`,`message`,`status_to` as `status`,'1' as `isSent`,`userID_to` FROM `messages` WHERE `userID_from`='" . $_SESSION['uid'] . "' AND `status_from` = '2') ORDER BY `messageID` DESC";
  $msg_get = mysqli_query($GLOBALS['link'], $msg_query) or die("MySQL Error: Failed to get messages.");
  return $msg_get;
}

//grabs all the unread messages sent to the user.
function getUnreadMessages(){
  // Grabs all the unread messages sent to this user
  $msg_query = "SELECT `messageID` FROM `messages` WHERE `userID_to`='" . $_SESSION['uid'] . "' AND `status_to` = '0'";
  $msg_get = mysqli_query($GLOBALS['link'], $msg_query) or die("MySQL Error: Failed to get unread messages.");
  // And returns the number of messages as the number of rows returned from the MySQL query
  return mysqli_num_rows($msg_get);
}

  //lets encrypt the password. for fun.
function hashPassword($pPassword, $salt="##WHatIsuP?!*", $pepper="1N0tMuCH#@0") {
    return sha1(md5($salt . $pPassword . $pepper));
  }
?>

<script>
  function checkValid(textBox){
    if(textBox.value == ""){

      alert("Field cannot be empty.");
      textBox.style.borderColor = "blue";
    }
  }

  function formValidation(){
    var name = document.forms["contact"]["name"].value;
    var email = document.forms["contact"]["email"].value;
    var sub = document.forms["contact"]["subject"].value;
    var comm = document.forms["contact"]["comments"].value;

    if(name == "" || sub == "" || email == "" ||comm == ""){
      alert("All fields must be filled out.");
      return false;
    }
    else{
      return true;
    }
  }
</script>
