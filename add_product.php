<?php
session_start();
if (!isset($_SESSION['user'])) {
    die("Մուտք գործեք՝ ապրանք ավելացնելու համար");
}

require "../config/Database.php";
require "../classes/Product.php";

$db = (new Database())->connect();
$product = new Product($db);

if ($_POST) {
    $product->add($_POST['name'], $_POST['price']);
}
?>

<form method="post">
    Name: <input name="name"><br>
    Price: <input name="price"><br>
    <button>Add</button>
</form>
