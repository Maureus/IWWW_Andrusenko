<?php
include "header.php";
if (!isset($_SESSION['userId'])) {
    header("Location: ../index.php?error=nouser");
    exit();
}
?>


<main class="wrapper-shop">
    <section style="width: auto;">
        <h2 class="text-white">My orders</h2>
        <?php
        require 'includes/dbh.inc.php';
        global $conn;
        $sql = "SELECT i.name, i.img, o.total_price, o.quantity FROM " . ITEMS_TABLE . " i JOIN " . ORDER_TABLE_MM . " o ON i.id = o.item_id where user_id = ?";
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
            $orderItems[] = $row;
            $numOfRows++;
        }

        mysqli_free_result($result);
        mysqli_stmt_close($stmt);


        if ($numOfRows > 0) {
            foreach ($orderItems as $item) {
                echo '
                            <div class="cart-item">
                            <div class="cart-img">                                
                                ' . $item["img"] . '
                            </div>
                            <div>
                                <p class="text-white">' . $item["name"] . '</p>
                            </div>
                            <div class="cart-control">                                
                                <div class="cart-quantity">
                                    <p class="text-white">Quantity: ' . $item["quantity"] . '</p>                                    
                                </div>
                                <div><p class="text-white">Total price: ' . $item["total_price"] . ' </p></div>                                
                            </div>                            
                            </div>';

            }
        }
        ?>
    </section>

</main>

<?php
include "footer.php";
?>
