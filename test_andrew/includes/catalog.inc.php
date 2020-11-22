<?php
require 'dbh.inc.php';
global $conn;
global $cartItems;

$catalog = fetchItemsCatalog($conn);
$numOfRows = fetchCart($conn);

function getBy($att, $value, $array) {
    foreach ($array as $key => $val) {
        if ($val[$att] == $value) {
            return $key;
        }
    }
    return null;
}

if (isset($_GET["action"])) {
    if ($_GET["action"] == "add" && !empty($_GET["id"])) {
        addToCart($_GET["id"]);
        header("Location: ../cart.php");
        exit();
    }

    if ($_GET["action"] == "remove" && !empty($_GET["id"])) {
        removeFromCart($_GET["id"]);
        header("Location: ../cart.php");
        exit();
    }

    if ($_GET["action"] == "delete" && !empty($_GET["id"])) {
        deleteFromCart($_GET["id"]);
        header("Location: ../cart.php");
        exit();
    }

    if ($_GET["action"] == "buy") {
        purchaseCart();
        header("Location: ../orders.php");
        exit();
    }
}

function addToCart($productId)
{
    session_start();
    global $catalog;
    global $conn;

    if (!array_key_exists($productId, $_SESSION["cart"])) {

        $item = $catalog[getBy("id", $productId, $catalog)];
        $sql = "INSERT INTO " . CART_TABLE_MM . "(price, item_id, user_id, quantity) VALUES(?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../index.php?error=sqlerror1");
            exit();
        }

        $quantity = 1;
        mysqli_stmt_bind_param(
            $stmt,
            "siii",
            $item['price'],
            $item['id'],
            $_SESSION['userId'],
            $quantity);

        if (mysqli_stmt_execute($stmt) == false) {
            header("Location: ../index.php?error=" . $item['price']);
            exit();
        }

        $_SESSION["cart"][$productId]["quantity"] = $quantity;

    } else {
        $_SESSION["cart"][$productId]["quantity"]++;
        $sql = "UPDATE " . CART_TABLE_MM . " set quantity = ? where item_id = ? and user_id = ?";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../index.php?error=sqlerror3");
            exit();
        }

        mysqli_stmt_bind_param(
            $stmt,
            "iii",
            $_SESSION["cart"][$productId]["quantity"],
            $productId,
            $_SESSION["userId"]);
        mysqli_stmt_execute($stmt);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

function removeFromCart($productId) {
    session_start();
    global $conn;
    if (array_key_exists($productId, $_SESSION["cart"])) {
        if ($_SESSION["cart"][$productId]["quantity"] <= 1) {
            deleteFromCart($productId);
        } else {
            $_SESSION["cart"][$productId]["quantity"]--;
            $sql = "UPDATE " . CART_TABLE_MM . " set quantity = ? WHERE item_id = ? and user_id = ?";
            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../index.php?error=sqlerror");
                exit();
            }

            mysqli_stmt_bind_param(
                $stmt,
                "iii",
                $_SESSION["cart"][$productId]["quantity"],
                $productId,
                $_SESSION["userId"]);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);

        }
    }
}

function deleteFromCart($productId) {
    session_start();
    global $conn;
    unset($_SESSION["cart"][$productId]);
    $sql = "DELETE FROM " . CART_TABLE_MM . " WHERE item_id = ? and user_id = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../index.php?error=sqlerror");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ii", $productId, $_SESSION["userId"]);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function purchaseCart() {
    session_start();
    global $conn;

    $sql = "SELECT c.item_id, i.name, i.img, c.price, c.quantity, c.user_id FROM " . ITEMS_TABLE . " i JOIN " . CART_TABLE_MM . " c ON i.id = c.item_id where user_id = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../index.php?error=sqlerror");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $_SESSION['userId']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $cartItems[] = $row;
    }

    mysqli_free_result($result);
    mysqli_stmt_close($stmt);

    foreach ($cartItems as $item) {
        moveToOrdersAndDelete($conn, $item);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

function moveToOrdersAndDelete($conn, $item) {
    $sql = "INSERT INTO " . ORDER_TABLE_MM . "(total_price, item_id, user_id, quantity) VALUES(?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../index.php?error=sqlerror1");
        exit();
    }

    $totalPrice = $item['price'] * $item['quantity'];
    $strTotal = strval($totalPrice);
    mysqli_stmt_bind_param(
        $stmt,
        "siii",
        $strTotal,
        $item['item_id'],
        $_SESSION['userId'],
        $item['quantity']);

    if (mysqli_stmt_execute($stmt) == false) {
        header("Location: ../index.php?error=sqlExecuteErr");
        exit();
    }

    deleteFromCart($item['item_id']);
}

function fetchItemsCatalog($conn) {
    $sql = "SELECT * FROM " . ITEMS_TABLE;
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../index.php?error=sqlerror");
        exit();
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $catalog[] = $row;
    }

    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
    return $catalog;
}

function fetchCart($conn) {
    $sql = "SELECT c.item_id, i.name, i.img, c.price, c.quantity, c.user_id FROM " . ITEMS_TABLE . " i JOIN " . CART_TABLE_MM . " c ON i.id = c.item_id where user_id = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../index.php?error=sqlerror");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $_SESSION['userId']);

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $numOfRows = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $cartItems[] = $row;
        $numOfRows++;
        $_SESSION["cart"][strval($row['item_id'])]["quantity"] = $row['quantity'];
    }

    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
    return $numOfRows;
}

