<?php
if (isset($_POST["reset-request-submit"])) {
  if (isset($_POST["email"])) {
    $selector = bin2hex(random_bytes(8));
    $token = random_bytes(32);

    $url = "localhost/test_andrew/create-new-password.php?selector=" . $selector . "&validator=" . bin2hex($token);
    $expires = date("U") + 1800;

    require "dbh.inc.php";
    require 'C:\xampp\htdocs\test_andrew\phpmailer.php';

    $userEmail = $_POST["email"];

    $sql = "DELETE FROM " . PR_TABLE . " WHERE email=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("Location: ../reset-password.php?error=sqlerror");
      exit();
    } else {
      mysqli_stmt_bind_param($stmt, "s", $userEmail);
      mysqli_stmt_execute($stmt);
    }

    $sql = "INSERT INTO " . PR_TABLE . " (email, selector, token,	expires) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("Location: ../reset-password.php?error=sqlerror");
      exit();
    } else {
      $hashedToken = password_hash($token, PASSWORD_DEFAULT);
      mysqli_stmt_bind_param($stmt, "ssss", $userEmail, $selector, $hashedToken, $expires);
      mysqli_stmt_execute($stmt);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    $message = '<p>We received a password reset request. The link to Reset
    your password will be below. If you did not make this request, please ignore
    this message.</p>';
    $message .= '<p>Here is your password reset link: </br>';
    $message .= '<a href="' . $url . '">' . $url . '</a></p>';

    try {
      $mail->SMTPDebug = 2;                      // Enable verbose debug output
      $mail->isSMTP();                                            // Send using SMTP
      $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
      $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
      $mail->Username   = 'sswepss@gmail.com';                     // SMTP username
      $mail->Password   = 'qhncpwwezmminmgm';                               // SMTP password
      $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
      $mail->Port       = 587;                                      // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

      // Content
      $mail->isHTML(true);                                  // Set email format to HTML
      //Recipients
      $mail->setFrom('no-reply@localhost.com');
      $mail->addAddress($userEmail);     // Add a recipient
      // Content
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = "Reset your password for localhost";
      $mail->Body    = $message;

      $mail->send();
      echo 'Message has been sent';
      header("Location: ../reset-password.php?reset=success");
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
  } else {
    header("Location: ../reset-password.php");
    exit();
  }
} else {
  header("Location: ../index.php");
  exit();
}
