<?php
  require_once("_header.php");
  connect();

  if(!authCheck())
    header("Location: /~daw277/register.php");

  $status = $to = $sub = $msg = "";
  if(!empty($_POST)){
    if(isset($_POST['newMsg'])){
      $to = protect($_POST['to']);
      $sub = protect($_POST['sub']);
      $msg = protect($_POST['msg']);

      if(empty($to) || empty($msg)){
        $status = "All fields are required.";
      }
      else{
        $user_check = mysqli_query($GLOBALS['link'], "SELECT `userID` FROM `users` WHERE `username`='$to' AND `isActive`='1'") or die("MySQL Error: Couldn't grab user data");
        if(mysqli_num_rows($user_check) == 1){
          $user_fetch = mysqli_fetch_assoc($user_check);
          $userID_to = $user_fetch['userID'];
          if($userID_to != $_SESSION['uid']){
            $msg_UserStr  = "INSERT INTO `messages` (`userID_to`,`userID_from`,`date`,`subject`,`message`) ";
            $msg_UserStr .= "VALUES ('$userID_to', '" . $_SESSION["uid"] . "','" . date("Y-m-d H:i:s") . "', '$sub','$msg')";
            $msg_User = mysqli_query($GLOBALS['link'], $msg_UserStr) or die("Failed to create message" . mysqli_error($GLOBALS['link']));
            $status = "Message Sent!";
            $to = $sub = $msg = "";
          }
          else{
            $status = "You can't send a message to yourself.";
          }
        }
        else{
          $status = "User does not exist.";
        }
      }
    }

    elseif(isset($_POST['delete'])){
      $messageID = protect($_POST['msgID']);

      $msg_query = "(SELECT `messageID`,'0' as `isSent` FROM `messages` WHERE `userID_to`='" . $_SESSION['uid'] . "' AND `status_to` != '-1' AND `messageID` = '$messageID') UNION ALL (SELECT `messageID`,'1' as `isSent` FROM `messages` WHERE `userID_from`='" . $_SESSION['uid'] . "' AND `status_from` = '2' AND `messageID`='$messageID')";
      $msg_get = mysqli_query($GLOBALS['link'], $msg_query) or die("MySQL Error: Failed to get messages to mark as deleted.");

      if(mysqli_num_rows($msg_get) == 1){
        $msg_fetch = mysqli_fetch_assoc($msg_get);
        $msg_query = "";
        if($msg_fetch['isSent'] == '0'){
          $msg_query = "UPDATE `messages` SET `status_to`='-1' WHERE `messageID` = '" . $msg_fetch['messageID'] . "'";
        }
        elseif($msg_fetch['isSent'] == '1'){
          $msg_query = "UPDATE `messages` SET `status_from`='-1' WHERE `messageID` = '" . $msg_fetch['messageID'] . "'";
        }
        if(!empty($msg_query)){
          $msg_update = mysqli_query($GLOBALS['link'], $msg_query) or die("MySQL Error: Failed to update message status to read.");
          $status = "Message status updated!";
        }
      }
    } elseif(isset($_POST['msgRead'])){
      $messageID = protect($_POST['msgID']);
      $msg_query = "SELECT `messageID` FROM `messages` WHERE `userID_to` = '" . $_SESSION['uid'] . "' AND `status_to` = '0' AND `messageID` = '" . $messageID . "'";
      $msg_get = mysqli_query($GLOBALS['link'], $msg_query) or die("MySQL Error: Failed to get messages to mark unread.");
      if(mysqli_num_rows($msg_get) == 1){
        $msg_fetch = mysqli_fetch_assoc($msg_get);
        $msg_query = "UPDATE `messages` SET `status_to`='1' WHERE `messageID` = '" . $msg_fetch['messageID'] . "'";
        $msg_update = mysqli_query($GLOBALS['link'], $msg_query) or die("MySQL Error: Failed to update message status to read.");
        $status = "Message status updated!";
      }
    }
  }
?>
<h1>Messages</h1>
<p><?php echo $status;?></p>

<form action="" method="post" class="sendMsg">
  <h2>Send a Message</h2>
  <p>To: <input name="to" value="<?php echo $to; ?>" type="text" placeholder="User"/></p>
  <!-- <p>Subject: <input name="sub" value="<?php echo $sub; ?>" type="text" placeholder="Subject"/></p> -->
  <p>Message: </p>
  <textarea placeholder="Message" name="msg" rows = "5" cols="50"><?php echo $msg; ?></textarea>
  <p><input class = "button" type="submit" name="newMsg" value="Send"/></p>
  <div class="clear"></div>
</form>

<h2>Your Messages:</h2>
<hr/>
<div class="messages">
    <?php

    $msgs = getMessages();
    $buttons = '<input class = "button" name="delete" value="Delete" type="submit" />';

    if(mysqli_num_rows($msgs) == 0){
      echo "<p>No new messages</p>";
    }

    else{
      while($msg = mysqli_fetch_assoc($msgs)){
        echo "<br><br>";
        if($msg['isSent'] === "0"){
          echo '<form class="message" action="" method="post">';
          echo '<input type="hidden" name="msgID" value="' . $msg['messageID'] . '"/>';
          //echo '<h1>' . $msg['subject'] . '</h1>';
          echo '<p>From: ' . getUsername($msg['userID']) . ' | ' . $msg['date'] . '</p>';
          echo '<p>Message Status: ';
          switch($msg['status']){
            case "0":
            echo "Unread</p>";
            break;
            case "1":
            echo "Read</p>";
            break;
            default:
            echo "</p>";
            break;
          }

          echo $msg['message'];
          echo '</br>';
          echo '</br>';
          echo '<div class="buttons">';
          if($msg['status'] === "0"){
            echo '<input class= "button" name="msgRead" value="Mark as Read" type="submit"/>';
          }
          echo $buttons;
          echo '</div>';
          echo '</form>';
          echo '</br>';
          echo '<hr/>';
        }

        elseif($msg['isSent'] === "1"){
          echo '<form class="message reply" action="" method="post">';
          echo '<input type="hidden" name="msgID" value="' . $msg['messageID'] . '"/>';
          //echo '<h1>Sent: ' . $msg['subject'] . '</h1>';
          echo '<p>Sent to: ' . getUsername($msg['userID']) . ' | ' . $msg['date'] . '</p>';
          echo '<p>Receiver Message Status: ';
          switch($msg['status']){
            case "0":
            echo "Unread";
            break;
            case "1":
            echo "Read";
            break;
            default:
            echo "";
            break;
          }
          echo '</p>';

          echo $msg['message'];
          echo '</br>';
          echo '</br>';
          echo '<div class="buttons">';
          echo $buttons;
          echo '</div>';
          echo '</br>';
          echo '<hr/>';
          echo '</form>';
          }
        }
    }
    ?>
</div>
<?php
  require_once("_footer.php");
?>
