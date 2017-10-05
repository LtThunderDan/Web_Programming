<?php
  require_once("_header.php");
  connect();

  //redirect to index if user is not logged on.
  if(!authCheck())
    header("Location: /~daw277/index.php");

  //get user info, written in _functions.php
  getUserInfo();

  //creates some variables
  //status used for messaging the user
  //user used to grad array
  $status = "";
  $user = $_SESSION['user'];

  //if there is a post, get the password.
  if(!empty($_POST)){
    //get the password and protect it.
    $curPass = protect($_POST['curPassword']);

    //if user didnt fill out current password. Tell them.
    if(empty($curPass)){
      $status = "Please enter your current password";
    }
    //otherwise, if they request to change the password, do this:
    else{
      if(isset($_POST['changePassword'])){
        //get and protect the users new password
        $newPass = protect($_POST['newPassword']);
        $verPass = protect($_POST['verPassword']);

        //variables to see if the password meets requirements.
        $uppercase = preg_match('@[A-Z]@', $newPass);
        $lowercase = preg_match('@[a-z]@', $newPass);
        $number    = preg_match('@[0-9]@', $newPass);

        //if fields are empty, tell user.
        if(empty($newPass) || empty($verPass)){
          $status = "All password fields must be filled in to change your passsword.";
        }

        //if the users new password does not meet requirements.
        elseif(!$uppercase || !$lowercase || !$number || strlen($newPass) < 8) {
          $status = "New Password requirements: must contain an upper and lower case, must contain a number, and must be longer then 8 characters.";
        }

        //if the new password and verification password dont match.
        elseif($newPass != $verPass) {
          $status = "The new passwords did not match... Try again.";
        }

        //if all is good, get the old password.
        else{
          $login_check = mysqli_query($GLOBALS['link'], "SELECT `password` FROM `users` WHERE `userID`='".$_SESSION['uid']."'") or die();
          $get_userInfo = mysqli_fetch_assoc($login_check);

          //use hash to protect the password.
          $hashPassword = hashPassword($curPass);

          //if hashPassword is equal to old password, all is good, and replace the passwords.
          if($hashPassword === $get_userInfo['password']){
            $users_chgPassword = mysqli_query($GLOBALS['link'], "UPDATE `users` SET `password`='" . hashPassword($newPass) . "' WHERE `userID`='".$_SESSION['uid']."'") or die();

            //log the fact that the user changed the password.
            $log_UserStr = "INSERT INTO `logs_users` (`type`,`date`,`userID`) ";
            $log_UserStr .= "VALUES ('3', '" . date("Y-m-d H:i:s") . "', '".$_SESSION['uid']."')";
            $log_User = mysqli_query($GLOBALS['link'], $log_UserStr) or die("Failed to create user log");

            //let user know.
            $status = "Password successfully changed!";
          }

          //otherwise, if the user didn't enter the correct password.
          else{
            $status = "Incorrect user password.";
          }
        }
      }

      //now, if the user selected to delete the user.
      elseif(isset($_POST['deleteUser'])){
        //get status of checkbox
        $confirmDelete = isset($_POST['confirmDelete']);

        //if status was not checked
        if(!$confirmDelete){
          $status = "Check box to delete account.";
        }

        //else if status was checked.
        else{
          //get users password.
          $login_check = mysqli_query($GLOBALS['link'], "SELECT `password` FROM `users` WHERE `userID`='".$_SESSION['uid']."'") or die();
          $get_userInfo = mysqli_fetch_assoc($login_check);

          //hash password that was entered in feild
          $hashPassword = hashPassword($curPass);

          //if password entered matches the password in our database delete/set inactive.
          if($hashPassword === $get_userInfo['password']){
            $deleteUser = mysqli_query($GLOBALS['link'], "UPDATE `users` SET `isActive`='0' WHERE `userID`='".$_SESSION['uid']."'") or die();

            //log that the use was deleted for our records.
            $log_UserStr = "INSERT INTO `logs_users` (`type`,`date`,`userID`) ";
            $log_UserStr .= "VALUES ('-1', '" . date("Y-m-d H:i:s") . "', '".$_SESSION['uid']."')";
            $log_User = mysqli_query($GLOBALS['link'], $log_UserStr) or die("Failed to create user log");

            //terminate/ destory user session. Then make a new one.
            session_destroy();
            session_start();

            //send to index.
            header("Location: /~daw277/index.php");
          }

          //otherwise, if the password did not match the databases.
          else{
            $status = "Please enter your correct password.";
          }
        }
      }
    }
  }
?>

<h1>Settings</h1>
<p><?php echo $status;?></p>

<form action="" method="post" style="margin-bottom: 20px;">
  <legend>Change User Account</legend>
  <table>
    <tbody>
      <tr>
        <td>Current Password</td>
        <td><input name="curPassword" type="password" /></td>
      </tr>

      <tr>
        <td><br></td>
        <td></td>
      </tr>

      <tr>
        <th colspan="2">
          New Password
        </th>
      </tr>
      <tr>
        <td>New Password</td>
        <td><input name="newPassword" type="password" /></td>
      </tr>
      <tr>
        <td>Repeat New Password</td>
        <td><input name="verPassword" type="password" /></td>
      </tr>
      <tr>
        <td></td>
        <td class="right"><button name="changePassword" value="Change Password" type="submit">Submit</button></td>
      </tr>

      <tr>
        <th colspan="2">
          Delete User Account
        </th>
      </tr>
      <tr>
        <td>Confirm Account Deletion? </td>
        <td><input name="confirmDelete" type="checkbox" /></td>
      </tr>
      <tr>
        <td></td>
        <td><button class="right" name="deleteUser" value="Delete User" type="submit">Delete User</button></td>
      </tr>
    </tbody>
  </table>
</form>

<table>
  <thead>
    <tr>
      <th colspan="2">User Account Log</th>
    </tr>
    <tr>
      <th>Log ID</th>
      <th>Timestamp</th>
      <th>Action</th>
    </tr>
  </thead>

  <tbody>
  <?php
  //for printing out the users logs.
  //uses a while loop to print out each row in the ID table.
  $userLogs = getUserLogs();
  while($row = mysqli_fetch_assoc($userLogs)){
    echo "
    <tr>
      <td>{$row['logID']}</td>
      <td>{$row['date']}</td>
      <td>{$G_logs_users_types[$row['type']]}</td>
    </tr>";
  }
  ?>

  </tbody>
</table>
<?php
  require_once("_footer.php");
?>
