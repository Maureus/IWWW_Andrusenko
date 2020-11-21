<?php
require "dbh.inc.php";
global $conn;
if (isset($_POST['update-user-submit'])) {
    session_start();
    $password = $_POST['pwd'];
    $email = $_POST['email'];
    $login = $_POST['login'];
    $id = $_POST['id'];
    $role = $_POST['role'];

    if (empty($password) || empty($email) || empty($login)) {
        header("Location: ../dashboard.php?error=emptyfield");
        exit();
    }

    if ($_SESSION['userRole'] == 'admin') {

        $sql = "UPDATE " . USER_TABLE . " SET password = ?, email = ?, login = ?, role = ? WHERE id = ?";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../dashboard.php?error=sqlerror");
            exit();
        }

        $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt, "sssss", $hashedPwd, $email, $login, $role, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: ../dashboard.php?update=success");
        exit();

    } else if ($_SESSION['userRole'] == 'user') {

        $sql = "UPDATE " . USER_TABLE . " SET password = ?, email = ?, login = ? WHERE id = ?";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../dashboard.php?error=sqlerror");
            exit();
        }

        $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
        session_start();
        mysqli_stmt_bind_param($stmt, "sssi", $hashedPwd, $email, $login, $_SESSION["userId"]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $_SESSION["userUid"] = $login;
        $_SESSION["userEmail"] = $email;

        header("Location: ../dashboard.php?update=success");
        exit();
    }
}

header("Location: ../dashboard.php?error=unknown");
exit();

