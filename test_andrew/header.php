<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Loggin Form Test</title>
  <link rel="stylesheet" href="./css/style.css">

</head>

<body>
  <header>
    <div>
      <nav class="nav-header-main">
        <div style="display: flex; padding-left: 1rem;">
          <a class="mr-2 text-white" href="index.php">Home</a>
          <?php
          if (isset($_SESSION["userId"])) {
            echo '<a class="mr-2 text-white" href="dashboard.php">Dashboard</a>';
          }
          ?>
        </div>
        <div class="header-login" style="display: flex;">
          <?php
          if (isset($_SESSION["userId"])) {
            echo '<div class="text-white mr-2">email: ' . $_SESSION["userEmail"] . '</div>
            <div class="text-white mr-2">role: ' . $_SESSION["userRole"] . '</div>
            <form class="mr-2" action="includes/logout.inc.php" method="post">
                <button class="rounded" type="submit" name="logout-submit">Logout</button>
              </form>';
          } else {
            echo '<form class="mr-2" action="includes/login.inc.php" method="post">
                <input type="text" name="mailuid" placeholder="Username/E-mail...">
                <input type="password" name="pwd" placeholder="Password...">
                <button type="submit" name="login-submit">Login</button>
              </form>
              <a class="mr-2 text-white" href="signup.php">Signup</a>';
            if (isset($_GET['error'])) {
              if ($_GET['error'] == "wrongpwd") {
                echo '<a class="text-white" href="reset-password.php">Forgot your password?</a>';
              }
            }
          }
          ?>



          </form>
        </div>
      </nav>
    </div>
  </header>