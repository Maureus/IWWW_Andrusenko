<?php
include "header.php";
require "./includes/catalog.inc.php";
if (!isset($_SESSION['userId'])) {
    header("Location: ../index.php?error=nouser");
    exit();
}
?>


<main class="wrapper-shop">
    <section style="width: auto;">
        <h2 class="text-white">Shopping cart</h2>
        <?php
        global $catalog;
        global $conn;
        global $numOfRows;

        if (isset($_SESSION['userId'])) {
            $totalPrice = 0;
            if ($numOfRows > 0) {
                foreach ($_SESSION["cart"] as $key => $value) {
                    $item = $catalog[getBy("id", $key, $catalog)];
                    $totalPrice = $totalPrice + ($value["quantity"] * $item["price"]);
                    echo '
                            <div class="cart-item">
                            <div class="cart-img">                                
                                ' . $item["img"] . '
                            </div>
                            <div class="text-white">
                                ' . $item["name"] . '
                            </div>
                            <div class="cart-control">
                                <div class="cart-price">
                                    <p class="text-white">' . $item["price"] . '</p>                                     
                                </div>
                                <div class="cart-quantity">
                                    <p class="text-white">' . ($value["quantity"]) . '</p>                                    
                                </div>
                                <div class="cart-quantity">
                                    <p class="text-white">' . ($value["quantity"] * $item["price"]) . '</p>                                    
                                </div>
                                <a href="./includes/catalog.inc.php?action=add&id=' . $item["id"] . '" class="cart-button">
                                    +
                                </a>
                                <a href="./includes/catalog.inc.php?action=remove&id=' . $item["id"] . '" class="cart-button">
                                    -
                                </a>
                                <a href="./includes/catalog.inc.php?action=delete&id=' . $item["id"] . '" class="cart-button">
                                    x
                                </a>
                            </div>
                            </div>';

                }
            }

            echo '<div id="cart-total-price" class="text-white">Total price: '.$totalPrice.'</div>';

            if ($totalPrice > 0) {
                echo '<a href="./includes/catalog.inc.php?action=buy" class="cart-button">Purchase</a>';
            }
        }
        ?>
    </section>

</main>

<?php
include "footer.php";
?>
