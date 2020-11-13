<?php
if (isset($_POST['signup-submit'])) {

  require "dbh.inc.php";

  $username = $_POST['uid'];
  $email = $_POST['mail'];
  $password = $_POST['pwd'];
  $passwordRepeat = $_POST['pwdRepeat'];

  if (empty($username) || empty($email) || empty($password) || empty($passwordRepeat)) {
    header("Location: ../signup.php?error=emptyfields&uid=" . $username . "&mail=" . $email);
    exit();
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)) {
    header("Location: ../signup.php?error=invalidmailuid");
    exit();
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../signup.php?error=invalidmail&uid=" . $username);
    exit();
  } elseif (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
    header("Location: ../signup.php?error=invaliduid&mail=" . $email);
    exit();
  } elseif ($password !== $passwordRepeat) {
    header("Location: ../signup.php?error=passwordcheck&uid=" . $username . "&mail=" . $email);
    exit();
  } else {
    $sql = "SELECT login FROM " . USER_TABLE . " WHERE login=?";
    $stmt = mysqli_stmt_init($conn);

    $sql2 = "SELECT email FROM " . USER_TABLE . " WHERE email=?";
    $stmt2 = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql) || !mysqli_stmt_prepare($stmt2, $sql2)) {
      header("Location: ../signup.php?error=sqlerror");
      exit();
    } else {
      mysqli_stmt_bind_param($stmt, "s", $username);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);
      $resultCheck = mysqli_stmt_num_rows($stmt);

      mysqli_stmt_bind_param($stmt2, "s", $email);
      mysqli_stmt_execute($stmt2);
      mysqli_stmt_store_result($stmt2);
      $resultCheck2 = mysqli_stmt_num_rows($stmt2);

      if ($resultCheck > 0 || $resultCheck2 > 0) {
        header("Location: ../signup.php?error=usertaken");
        exit();
      } else {
        $sql = "INSERT INTO " . USER_TABLE . " (login, email, password) VALUES(?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          header("Location: ../signup.php?error=sqlerror");
          exit();
        } else {
          $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

          mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPwd);
          mysqli_stmt_execute($stmt);
          header("Location: ../signup.php?signup=success");
          exit();
        }
      }
    }
  }
  mysqli_stmt_close($stmt);
  mysqli_stmt_close($stmt2);
  mysqli_close($conn);
} else {
  header("Location: ../signup.php");
  exit();
}
