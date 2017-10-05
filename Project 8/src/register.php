<?php
  require_once("_header.php");
  connect();

  //if logged in, send too:
  if(authCheck())
    header("Location: /~daw277/index");

  //string variable to let user know what's is going on.
  $stringVar = "";

  //if we have a request by the user, create one.
  if(!empty($_POST)){
    if(isset($_POST['register'])){
      $user = protect($_POST['user']); //echo $user;
      $email = filter_var(protect($_POST['email']), FILTER_SANITIZE_EMAIL); //echo $email;
      $password = protect($_POST['password']); //echo $password;
      $valPassword = protect($_POST['valPassword']); //echo $valPassword;

      //echo var_dump($_POST);

      //if any field is empty, notify user. w/ echo
      if(empty($user) || empty($email) || empty($password) || empty($valPassword)){
        $stringVar = "Please fill in all necessary feilds.";

      //requirements for user name, if not met, notify user.
      } elseif(!preg_match('/^[A-Za-z][A-Za-z0-9]{5,19}$/', $user)){
        $stringVar = "Username must be 5-20 characters containing A-Z, a-z, and/or 0-9. Must also start with a letter.";

      //if user did not enter a vailid email, let them know.
      } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $stringVar = "Invalid Email.";

      //if all is good, lets check if email and user name hasn't been used.
      //by getting all the already active users and comparing them.
      } else{
        $usedUserNames = mysqli_query($GLOBALS['link'], "SELECT `userID` FROM `users` WHERE `username`='$user' AND `isActive`='1'") or die();
        $usedEmails = mysqli_query($GLOBALS['link'], "SELECT `userID` FROM `users` WHERE `email`='$email' AND `isActive`='1'") or die();

        //if a user name is found, let the user know it is already taken.
        if(mysqli_num_rows($usedUserNames)){
          $stringVar = "That username is already taken! Try again.";

        //if a users email is found, let the user konw it is already taken.
        } else if(mysqli_num_rows($usedEmails)){
          $stringVar = "That email address is already taken! Try again.";

        //if all feilds are full AND pass all requirements AND aren't already in use, check to see if the password meets the requirements.
        } else{
          $upper = preg_match('@[A-Z]@', $password);
          $lower = preg_match('@[a-z]@', $password);
          $number = preg_match('@[0-9]@', $password);

          //password requirements: must contain an upper and lower case, must contain a number, and must be longer then 8 characters.
          if(!$upper || !$lower || !$number || strlen($password) < 8) {
            $stringVar = "Password requirements: must contain an upper and lower case, must contain a number, and must be longer then 8 characters.";

          //if the validation password did not match the orginal password. Let the user know.
          } elseif($password != $valPassword) {
             $stringVar = "Your passwords did not match! Try again.";
          }
        }
      }

      //if everything checks out, lets Encrypt the users password and send it off to our server.
      if(empty($stringVar)){
        $hashpassword = hashPassword($password);

        //create a spot for the newly registered user by adding their info into our database.
        $reg_UserStr = "INSERT INTO `users` (`password`,`email`,`username`) ";
        $reg_UserStr .= "VALUES ('$hashpassword', '$email', '$user')";
        $reg_User = mysqli_query($GLOBALS['link'], $reg_UserStr) or die("Failed");

        $get_UserID = mysqli_query($GLOBALS['link'], "SELECT LAST_INSERT_ID()") or die("Failed");
        $userID = mysqli_fetch_assoc($get_UserID) or die();

        $log_UserStr = "INSERT INTO `logs_users` (`type`,`date`,`userID`) ";
        $log_UserStr .= "VALUES ('0', '" . date("Y-m-d H:i:s") . "', '".$userID['LAST_INSERT_ID()']."')";
        $log_User = mysqli_query($GLOBALS['link'], $log_UserStr) or die("Failed");

        //let the user know the account is all good.
        echo '<p style="color:#008cba;">Your information was submitted!</p>';

        $_POST['user'] = $_POST['email'] = "";
      }
    }
  }
?>

<h1>Register</h1>
<form action="" method="post">
  <p style = "color: red;"><?php echo $stringVar; ?></p>
  <table>
    <thead></thead>

    <tbody>
      <tr>
        <td>Username:</td>
        <td><input type="text" name="user" placeholder="Username" value="<?php echo (isset($_POST['user'])) ? $_POST['user'] : '';?>" id="user"/></td>
      </tr>
      <tr>
        <td>Email:</td>
        <td><input type="text" name="email" placeholder="youremail@email.com" value="<?php echo (isset($_POST['email'])) ? $_POST['email'] : '';?>"/></td>
      </tr>
      <tr>
        <td>Password:</td>
        <td><input type="password" name="password"/></td>
      </tr>
      <tr>
        <td>Confirm Password:</td>
        <td><input type="password" name="valPassword" /></td>
      </tr>
    </tbody>
  </table>
  <button class="button" type="submit" name="register">Register</button>
</form>

<?php
  require_once("_footer.php");
?>
