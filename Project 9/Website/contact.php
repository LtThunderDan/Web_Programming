<?php
  require_once("_header.php");
 ?>

    <img src="Images/clouds.png" width="20%"><br/>
    <p><b>Contact Information:</b></p>
  <form method="POST" name="contact"  action="" onsubmit="return formValidation()">
  <table>
    <tr><p style="color: red;">
     <?php
      $drop = (isset($_POST['select'])) ? $_POST['select']: "select";
      $checkbox = (isset($_POST['checkbox'])) ? $_POST['checkbox']: "";
      $name = (isset($_POST['name'])) ? $_POST['name']: "";
      $email = (isset($_POST['email'])) ? $_POST['email']: "";
      $subject = (isset($_POST['subject'])) ? $_POST['subject']: "";
      $comments = (isset($_POST['comments'])) ? $_POST['comments']: "";
      $t = time();

        if(!empty($_POST)){
          /*
          radio = question,comment,urgent
          ? checkbox = reply, null
          name = John Doe
          email = john.doe@email.com
          subject = My Subject
          comments = My message to Daniel
          */
          $status = "";

          if(empty($name)){
            $status .= "*You did not enter a name.<br>";
          }

          if(empty($email)){
            $status .= "*You did not enter a email address.<br>";
          } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $status .= "*You did not enter a valid email address.<br>";
          }

          if(empty($subject)){
            $status .= "*You did not enter a subject.<br>";
          }

          if(empty($comments)){
            $status .= "*You did not enter any comments.<br>";
          }

          if(!empty($status)){
            echo $status;
          } else{
            $email_subject = "Your Contact Request Was Received";
            $message = "
            <html>
            <body>
              <h1>Thank you " . $name . " for your contact request.</h1>
              <p>I got your form submission and will respond as soon as possible!</p>
              <p>Below is the information that you submitted:</p>
              <p>Name: ".$name."</p>
              <p>Email: ".$email."</p>
              <p>Subject: ".$subject."</p>
              <p>Message: ".$comments."</p>
              <p>Type: ".ucwords($drop)."</p>
              <p>Reply Needed?: ".((isset($checkbox)) ? "Yes" : "No")."</p>
              <p>Date Received: ".date("Y-m-d",$t)."</p>
              <p>Best Regards,</p>
              <p>Daniel Williamson</p>
            </body>
            </html>
            ";

            $headers = "From: Daniel Williamson <daw277@nau.edu>\r\n" . "CC: daw277@nau.edu" . "\r\n";
            $headers .= "Reply-To: daw277@nau.edu\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            mail($email, $email_subject, $message, $headers);

            echo '<p style="color:#008cba;">Your information was submitted!</p>';
            $name = $email = $subject = $comments = "";
            $checkbox = "";
          }
        }
      ?>
    </p></tr>
    <tr>
      Reason for email:
      <select name="select">
        <option type = "select" name = "select" value="complaint" <?php echo ($drop == "complaint") ? "selected" : ""; ?>> Complaint</option>
        <option type = "select" name = "select" value="question" <?php echo ($drop == "question") ? "selected" : ""; ?>> Question</option>
        <option type = "select" name = "select" value="suggestion" <?php echo ($drop == "suggestion") ? "selected" : ""; ?>> Suggestion</option>
        <option type = "select" name = "select" value="other" selected = "selected" <?php echo ($drop == "other") ? "selected" : ""; ?>> Other</option>
      </select>
    </tr>
    <br/>
    <br/>
    <tr>
      Would you like a return email?  <input type="checkbox" name="checkbox" value="reply" >
    </tr>
    <tr>
      <td>First and Last Name:</td>
      <td><input type="text" name="name" placeholder="First Last" id="name" onblur= "checkValid(this);" value="<?php echo $name; ?>"></td>
    </tr>
    <tr>
      <td>Email Address:</td>
      <td><input type="text" name="email" placeholder="email@domain.com" id="email" onblur="checkValid(this);" value="<?php echo $email; ?>"></td>
    </tr>
    <tr>
      <td>Subject:</td>
      <td><input type="text" name="subject" placeholder="Subject" id= "subject" onblur= "checkValid(this);" value="<?php echo $subject; ?>"></td>
    </tr>
    <tr>
      <td>Message:</td>
      <td><textarea rows="5" name="comments" style="min-width: 150px;" placeholder="Message" id= "comments" onblur= "checkValid(this);"><?php echo $comments; ?></textarea></td>
    </tr>
  </table>
    <input type="submit" value="Send">
  </form>
  <a class="box" href="#top">Go to top</a>
  </div>
  </center>
  <div id="watermark">
    <img src="Images/clouds.png" width="20%">
  </div>
  <?php
    require_once("_footer.php");
  ?>
