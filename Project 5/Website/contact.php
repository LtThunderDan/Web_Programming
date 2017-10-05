<html>
<head>
  <title>Thunder</title>
  <link rel="shortcut icon" href="Images/vader.ico" />
  <link rel="stylesheet" type="text/css" href="Style/style.css" />
</head>
<body>
  <header>
    <ul class="SideBar">
      <li class="SideBar"><a href="https://en.wikipedia.org/wiki/Cloud" target="_blank">Wikipedia</a></li>
      <li class="SideBar"><a href="https://weather.com/weather/tenday/l/Flagstaff+AZ+USAZ0068:1:US" target="_blank">Forecast</a></li>
      <li class="SideBar"><a href="http://www.accuweather.com/en/us/national/weather-radar" target="_blank">Radar</a></li>
    </ul>
    <center>
      <img src="Images/header_pic.jpg" width="80%"><br/>
      <p id="top">
    </center>
      <div id="NavBar">
        <ul class="NavBar">
          <li class="NavBar"><a href="/~daw277/index.html">Home</a></li>
          <li class="NavBar"><a href="/~daw277/cloud.html">Clouds</a></li>
          <li class="NavBar"><a href="/~daw277/faq.html">FAQ</a></li>
          <li class="NavBar" style="float:right"><a href="/~daw277/contact.php">Contact</a></li>
        </ul>
      </div>
  </header>
  <center>
  <div id="text">
    <img src="Images/clouds.png" width="20%"><br/>
    <p><b>Contact Information:</b></p>
  <form method="POST" action="">
  <table>
    <tr><p style="color: red;">
      <?php
      $radio = (isset($_POST['radio'])) ? $_POST['radio']: "question";
      $checkbox = (isset($_POST['checkbox'])) ? $_POST['checkbox']: "";
      $name = (isset($_POST['name'])) ? $_POST['name']: "";
      $email = (isset($_POST['email'])) ? $_POST['email']: "";
      $subject = (isset($_POST['subject'])) ? $_POST['subject']: "";
      $comments = (isset($_POST['comments'])) ? $_POST['comments']: "";

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
              <p>Type: ".ucwords($radio)."</p>
              <p>Reply Needed?: ".((isset($checkbox)) ? "Yes" : "No")."</p>
              <p>Best Regards,</p>
              <p>Daniel Williamson</p>
            </body>
            </html>
            ";

            $headers = "From: Daniel Williamson <daw277@nau.edu>\r\n";
            $headers .= "Reply-To: daw277@nau.edu\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            mail($email, $email_subject, $message, $headers);

            echo '<p style="color:#008cba;">Your information was submitted!</p>';
            $name = $email = $subject = $comments = "";
            $radio = "question";
            $checkbox = "";
          }
        }
      ?>
    </p></tr>
    <tr>
      <p>What is your message?</p>
        <input type="radio" name="radio" value="question" <?php echo ($radio == "question") ? "checked" : ""; ?>> Question<br/>
        <input type="radio" name="radio" value="comment" <?php echo ($radio == "comment") ? "checked" : ""; ?>> Comment<br/>
        <input type="radio" name="radio" value="urgent" <?php echo ($radio == "urgent") ? "checked" : ""; ?>> Urgent<br/>
    </tr>
    <br/>
    <tr>
      Would you like a return email?  <input type="checkbox" name="checkbox" value="reply" <?php echo ($checkbox == "reply") ? "checked" : ""; ?>>
    </tr>
    <tr>
      <td>First and Last Name:</td>
      <td><input type="text" name="name" placeholder="First Last" value="<?php echo $name; ?>"></td>
    </tr>
    <tr>
      <td>Email Address:</td>
      <td><input type="text" name="email" placeholder="email@domain.com" value="<?php echo $email; ?>"></td>
    </tr>
    <tr>
      <td>Subject:</td>
      <td><input type="text" name="subject" placeholder="Subject" value="<?php echo $subject; ?>"></td>
    </tr>
    <tr>
      <td>Message:</td>
      <td><textarea rows="5" name="comments" style="min-width: 150px;" placeholder="Message"><?php echo $comments; ?></textarea></td>
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
  <footer>
    &copy Daniel Williamson<br/>
    Photo Images found at:
    <a href="https://en.wikipedia.org/wiki/Cloud" target="_blank">Wikipedia</a></br>
    <a href="http://cefns.nau.edu/~daw277/resume.html">Resume</a>
  </footer>
</body>
</html>
