<?php
$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "test_andrew";

$conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbName);

const USER_TABLE = "users";
const PR_TABLE = "password_resets";
const ITEMS_TABLE = "items";
const CART_TABLE_MM = "cart";
const ORDER_TABLE_MM = "orders";

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}
