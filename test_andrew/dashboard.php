<?php
include "header.php";
?>


<main>
  <div>
    <?php
    if ($_SESSION["userRole"] == "admin") {
      echo '<form class="mr-2" style="padding-top: 4rem;" action="dashboard.php" method="get">
        <button class="rounded" type="submit" name="show-users">Show users</button>
      </form>';
    }
    ?>
    <form class="mr-2" style="padding-top: 1rem;" action="dashboard.php" method="post">
      <?php
      if ($_SESSION["userRole"] == "admin") {
        echo '<input type="text" name="userId" placeholder="User_Id...">';
      }
      ?>
      <button class="rounded" type="submit" name="edit-user-submit">Edit user</button>
    </form>
  </div>
  <div>
    <?php
    if ($_SESSION["userRole"] == "admin") {
      echo '<div>
      <p class="text-white w-full">Welcome ' . $_SESSION["userRole"] . '!</p>      
            </div>';
      require "./includes/dbh.inc.php";
      if (isset($_GET["show-users"])) {
        $sql = "SELECT * FROM " . USER_TABLE;
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          header("Location: ../dashboard.php?error=sqlerror");
          exit();
        } else {
          mysqli_stmt_execute($stmt);
          $users = array();
          $result = mysqli_stmt_get_result($stmt);
          if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              $users[] = $row;
            }

            foreach ($users as $item) {
              echo '<div>
                  <p class="text-white w-full">ID: ' . $item["id"] . ' User: ' . $item["login"] . ' Email: ' . $item["email"] . ' Role: ' . $item["role"] . '</p>      
                </div>';
            }
          } else {
            echo '<div>
      <p class="text-white w-full">Error!</p>      
            </div>';
          }
        }
      }

      if (isset($_GET["edit-user"])) {
      }
    } else if ($_SESSION["userRole"] == "user") {
      echo '<div>
      <p class="text-white w-full">Welcome ' . $_SESSION["userRole"] . '!</p>      
            </div>';
    }
    ?>
  </div>
  <div class="wrapper-main">
    <?php
    if (isset($_GET['update'])) {
      if ($_GET['update'] == "success") {
        echo '<p class="text-white">User successfuly updated!</p>';
      }
    } else if (isset($_GET['error'])) {
      echo '<p class="text-white">Error!</p>';
    }
    if (isset($_POST["edit-user-submit"])) {
      if ($_SESSION["userRole"] == "admin") {
        echo '<div><p>Updating user with id: ' . $_POST["userId"] . '. </p><form class="mr-2 form-signup" action="includes/update-user.inc.php" method="post">
        <input class="w-full" type="text" name="id" value="' . $_POST["userId"] . '">
        <input class="w-full" type="text" name="pwd" placeholder="Password...">
        <input class="w-full" type="text" name="login" placeholder="login...">
        <input class="w-full" type="text" name="email" placeholder="email...">
        <input class="w-full" type="text" name="role" placeholder="role...">
        <button class="rounded test-white" type="submit" name="update-user-submit">Update</button>
      </form></div>';
      } else {
        echo '<div><p>Updating yourself id: ' . $_SESSION["userId"] . '.</p><form class="mr-2 form-signup" action="includes/update-user.inc.php" method="post">        
        <input class="w-full" type="text" name="pwd" placeholder="password...">
        <input class="w-full" type="text" name="login" placeholder="login...">
        <input class="w-full" type="text" name="email" placeholder="email...">        
        <button class="rounded test-white" type="submit" name="update-user-submit">Update</button>
      </form></div>';
      }
    }

    ?>

  </div>
</main>

<?php
include "footer.php";
?>