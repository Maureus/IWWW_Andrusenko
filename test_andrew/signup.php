<?php
include "header.php";
?>


<main>
  <div class="wrapper-main">
    <section class="section-default">
      <h1 class="text-white">Signup</h1>
      <?php
      if (isset($_GET["error"])) {
        if ($_GET["error"] == "emptyfields") {
          echo "<p class='signuperror'>Fill in all fields</p>";
        } elseif ($_GET["error"] == "invalidmailuid") {
          echo "<p class='signuperror'>Invalid username and e-mail</p>";
        } elseif ($_GET["error"] == "invaliduid") {
          echo "<p class='signuperror'>Invalid username</p>";
        } elseif ($_GET["error"] == "invalidmail") {
          echo "<p class='signuperror'>Invalid e-mail</p>";
        } elseif ($_GET["error"] == "passwordcheck") {
          echo "<p class='signuperror'>Yout pwds dont match</p>";
        } elseif ($_GET["error"] == "usertaken") {
          echo "<p class='signuperror'>Username or e-mail is already taken</p>";
        }
      } elseif (isset($_GET["signup"])) {
        if ($_GET["signup"] == "success") {
          echo "<p class='signupsuccess'>You have successfuly signed up!</p>";
        }
      }
      ?>
      <form class="form-signup" action="includes/signup.inc.php" method="post">
        <input class="mb-1 w-full rounded" type="text" name="uid" placeholder="Username">
        <input class="mb-1 w-full rounded" type="text" name="mail" placeholder="E-mail">
        <input class="mb-1 w-full rounded" type="password" name="pwd" placeholder="Password">
        <input class="mb-1 w-full rounded" type="password" name="pwdRepeat" placeholder="Repeat Password">
        <button class="mb-1 rounded" type="submit" name="signup-submit">Signup</button>
      </form>
      <a class="text-white" href="reset-password.php">Forgot your password?</a>
    </section>
  </div>
</main>

<?php
include "footer.php";
?>