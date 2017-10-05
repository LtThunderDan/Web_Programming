<?php
require_once("_functions.php");
connect();
?>

<html>
<head>
  <title>Thunder</title>
  <link rel="shortcut icon" href="Images/vader.ico" />
  <link rel="stylesheet" type="text/css" href="Style/style.css" />
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto">
</head>
<body>
  <header>
  <center>
    <img src="Images/header_pic.jpg" width="80%"><br/>
    <p id="top">
  </center>
    <div id="NavBar">
      <ul class="NavBar">
        <li class="NavBar"><a href="/~daw277/index.php">Home</a></li>
        <li class="NavBar"><a href="/~daw277/cloud.php">Clouds</a></li>
        <li class="NavBar"><a href="/~daw277/faq.php">FAQ</a></li>
        <li class="NavBar" style="float:right"><a href="/~daw277/contact.php">Contact</a></li>
      <?php
      //if logged in
      if(authCheck()){
      ?>
        <li class="NavBar" style="float:right;"><a href="/~daw277/messages.php">Messages</a></li>
        <li class="NavBar" style="float:right;"><a href="/~daw277/settings.php">Settings</a></li>
        <li class="NavBar" style="float:right;"><a href="/~daw277/logout.php">Logout</a></li>
      <?php
      //if not logged in
      } else {
      ?>
      <li class="NavBar" style="float:right;"><a href="/~daw277/register.php">Register</a></li>
      <li class="NavBar" style="float:right;"><a href="/~daw277/login.php">Login</a></li>
      <?php
      }
      ?>
      </ul>
    </div>
  </header>
    <ul class="SideBar">
      <li class="SideBar"><a href="https://en.wikipedia.org/wiki/Cloud" target="_blank">Wikipedia</a></li>
      <li class="SideBar"><a href="https://weather.com/weather/tenday/l/Flagstaff+AZ+USAZ0068:1:US" target="_blank">Forecast</a></li>
      <li class="SideBar"><a href="http://www.accuweather.com/en/us/national/weather-radar" target="_blank">Radar</a></li>
    </ul>
  <div id="text">
    <center>
