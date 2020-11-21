<?php
include "header.php";
?>

    <main>
        <div class="wrapper-main">
            <section class="section-default">
                <?php
                if (isset($_GET['error'])) {
                    if (isset($_GET['error']) == "passwordcheck") {
                        echo '<p>Passwords do not match!</p>';
                    } elseif (isset($_GET['error']) == "emptyfields") {
                        echo '<p>Please fill all the fields!</p>';
                    }
                }

                if (isset($_GET['selector']) && isset($_GET['validator'])) {

                    $selector = $_GET['selector'];
                    $validator = $_GET['validator'];

                    if (ctype_xdigit($selector) && ctype_xdigit($validator)) {

                        $token = hex2bin($validator);

                        require "includes/dbh.inc.php";

                        $sql = "SELECT * FROM " . PR_TABLE . " WHERE selector=?";
                        $stmt = mysqli_stmt_init($conn);
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            header("Location: ../index.php?error=sqlerror");
                            exit();
                        } else {
                            mysqli_stmt_bind_param($stmt, "s", $selector);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            mysqli_stmt_close($stmt);
                            if ($row = mysqli_fetch_assoc($result)) {
                                $currentDate = date("U");
                                if ($currentDate >= $row["expires"]) {
                                    echo "Your password reset token has expired";
                                    $sql2 = "DELETE FROM " . PR_TABLE . " WHERE selector=?";
                                    $stmt2 = mysqli_stmt_init($conn);
                                    if (!mysqli_stmt_prepare($stmt2, $sql2)) {
                                        header("Location: ../index.php?error=sqlerror");
                                        exit();
                                    } else {
                                        mysqli_stmt_bind_param($stmt2, "s", $selector);
                                        mysqli_stmt_execute($stmt2);
                                        mysqli_stmt_close($stmt2);

                                        header("Location: ../index.php?create=tokenexpired");
                                        exit();
                                    }
                                }
                                $tokenCheck = password_verify($token, $row["token"]);
                                if (!$tokenCheck) {
                                    echo "Could not validate your request";
                                } elseif ($tokenCheck) {
                                    echo '<form action="includes/create-new-password.inc.php" method="post">
                        <input type="hidden" name="email" value="' . $row["email"] . '">
                        <input type="hidden" name="selector" value="' . $selector . '">
                        <input type="hidden" name="validator" value="' . $validator . '">
                        <input type="password" name="pwd" placeholder="Passwod...">
                        <input type="password" name="pwdRepeat" placeholder="Repeat Password...">
                        <button type="submit" name="create-password-submit">Confirm</button>
                      </form>';
                                } else {
                                    echo "Could not validate your request";
                                    exit();
                                }
                            } else {
                                echo "Could not validate your request";
                                exit();
                            }
                        }
                    } else {
                        echo "Could not validate your request";
                    }
                    mysqli_close($conn);
                } else {
                    echo "Could not validate your request";
                }
                ?>
            </section>
        </div>
    </main>

<?php
include "footer.php";
?>