<?php
if (isset($_POST["login-submit"])) {
    require "dbh.inc.php";
    global $conn;

    $login = $_POST["mailuid"];
    $password = $_POST["pwd"];

    if (empty($login) || empty($password)) {
        header("Location: ../index.php?error=emptyfields");
        exit();
    }

    $sql = "SELECT * FROM " . USER_TABLE . " WHERE login=? OR email=?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../index.php?error=sqlerror");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $login, $login);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        $pwdCheck = password_verify($password, $row["password"]);
        if (!$pwdCheck) {
            header("Location: ../index.php?error=wrongpwd");
            exit();
        } elseif ($pwdCheck) {
            session_start();
            $_SESSION["userId"] = $row["id"];
            $_SESSION["userUid"] = $row["login"];
            $_SESSION["userEmail"] = $row["email"];
            $_SESSION["userRole"] = $row["role"];
            header("Location: ../dashboard.php");
            exit();
        } else {
            header("Location: ../index.php?error=unkownerror");
            exit();
        }
    }

    header("Location: ../index.php?error=nouser");
    exit();
}

header("Location: ../index.php");
exit();