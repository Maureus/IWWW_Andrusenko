<?php
include "header.php";
require "./includes/catalog.inc.php";
global $catalog;
?>


    <main class="wrapper-shop">
        <div>
            <section>
                <h2 class="text-white">Shop</h2>
            </section>
            <section id="catalog-items">
                <?php

                foreach ($catalog as $item) {
                    echo '
                                <div class="catalog-item">
                                    <div class="catalog-img">
                                        ' . $item["img"] . '
                                    </div>
                                    <h3>
                                        ' . $item["name"] . '
                                    </h3>
                                    <div>
                                        ' . $item["price"] . '
                                    </div>';
                    if (isset($_SESSION['userId'])) {
                        echo '<a href="./includes/catalog.inc.php?action=add&id=' . $item["id"] . '" class="catalog-buy-button">Buy</a>';
                    }
                    echo '</div>';
                }
                ?>
            </section>
        </div>
    </main>
<?php
include "footer.php";
?>
