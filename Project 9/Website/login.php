<?php
  require_once("_header.php");

  //checks to see if user is logged in, if not, redirect to index
  if(authCheck())
    header("Location: /~daw277/index.php");

    //set up a string for later
    $status = "";

  //if there is a post, AND a login request
  if(!empty($_POST)){
    if(isset($_POST['login'])){

      //we call protect to make sure the string is safe
      $user = protect($_POST['user']);
      $password = protect($_POST['password']);

      //takes all the info the user entered
      $login_check = mysqli_query($GLOBALS['link'], "SELECT `userID`,`username`,`password` FROM `users` WHERE `username`='$user' AND `isActive`='1'") or die("MySQL Error: Couldn't grab user data");

      //checks to see if the user name is exists, if nothing returns, the user does not exit
      if(mysqli_num_rows($login_check) == 0){
        $status = "Invalid Username and/or Password.";
      }

      //lets change the MySQL data into a PHP array
      else{
        $get_userInfo = mysqli_fetch_assoc($login_check);

        //use hashPassword to protect it
        $hashPassword = hashPassword($password);

        //if the password the user entered matches the database password
        if($hashPassword === $get_userInfo['password']){
          //then setup the User's ID in the Session array
          $_SESSION['uid'] = $get_userInfo['userID'];

          //change the user type to 1
          $log_UserStr = "INSERT INTO `logs_users` (`type`,`date`,`userID`) ";
          $log_UserStr .= "VALUES ('1', '" . date("Y-m-d H:i:s") . "', '".$_SESSION['uid']."')";
          $log_User = mysqli_query($GLOBALS['link'], $log_UserStr) or die("Failed to create user log");

          //send them back to index after logging in.
          header("Location: /~daw277/index.php");
        }

        //otherwise, if the password the user inputted was incorrect.
        else{
          $status = "Invalid Username and/or Password.";
        }
      }
    }
  }
?>

<h1>Login</h1>
<p style = "color: red;"><?php echo $status; ?></p>

<form action="" method="post">
  <table>
    <thead></thead>

    <tbody>
      <tr>
        <td>Username:</td>
        <td><input type="text" name="user" value="<?php echo (isset($_POST['user'])) ? $_POST['user'] : '';?>" /></td>
      </tr>
      <tr>
        <td>Password:</td>
        <td><input type="password" name="password"/></td>
      </tr>
    </tbody>
  </table>

  <button class="button" type="submit" name="login">Login</button>
</form>
<?php
  require_once("_footer.php");
?>
