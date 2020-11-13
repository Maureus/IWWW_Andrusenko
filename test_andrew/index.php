<?php
include "header.php";
?>


<main>
  <div class="wrapper-main">
    <section class="section-default">
      <?php
      if (isset($_SESSION["userId"])) {
        echo '<p class="login-status">' . $_SESSION["userUid"] . ' - you are logged in!</p>';
      } elseif (!isset($_SESSION["userId"])) {
        echo '<p class="login-status">You are logged out!</p>';
      }

      if (isset($_GET['create'])) {
        if ($_GET['create'] == "success") {
          echo '<p class="password-status created">Password successfuly changed!</p>';
        } elseif ($_GET['create'] == "tokenexpired") {
          echo '<p class="password-status expired">Password reset token has expired!</p>';
        }
      }
      ?>
    </section>
  </div>
</main>

<?php
include "footer.php";
?>