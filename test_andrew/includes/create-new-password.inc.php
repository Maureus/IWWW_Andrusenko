<?php
require "dbh.inc.php";
global $conn;

if (isset($_POST['create-password-submit'])) {
    $password = $_POST['pwd'];
    $passwordRepeat = $_POST['pwdRepeat'];
    $selector = $_POST['selector'];
    $validator = $_POST['validator'];
    $email = $_POST['email'];

    if (empty($password) || empty($passwordRepeat)) {
        header("Location: ../create-new-password.php?error=emptyfields&selector=" . $selector . "&validator=" . $validator);
        exit();

    } else if ($password !== $passwordRepeat) {
        header("Location: ../create-new-password.php?error=passwordcheck&selector=" . $selector . "&validator=" . $validator);
        exit();
    }

    $sql = "UPDATE " . USER_TABLE . " SET password = ? WHERE email = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../index.php?error=sqlerror");
        exit();
    }

    $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt, "ss", $hashedPwd, $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);


    $sql2 = "DELETE FROM " . PR_TABLE . " WHERE selector =?";
    $stmt2 = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt2, $sql2)) {
        header("Location: ../index.php?error=sqlerror");
        exit();
    }

    mysqli_stmt_bind_param($stmt2, "s", $selector);
    mysqli_stmt_execute($stmt2);
    mysqli_stmt_close($stmt2);
    mysqli_close($conn);

    header("Location: ../index.php?create=success");
    exit();

} else {
    header("Location: ../index.php");
    exit();
}
