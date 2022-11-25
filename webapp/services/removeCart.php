<?php
// remove item from cart (remove the item from the session)
session_start();
if (isset($_GET["action"])) {
    if ($_GET["action"] == "remove") {
        $removeQuantity = $_POST["numberRemove"];
        $removeID = $_POST["hidden_id"];
        $cart = $_SESSION["shopping_cart"];
        foreach ($cart as $keys => $values) {
            if ($values["item_id"] == $removeID) { // if the item is in the cart
                if ($values["item_quantity"] > $removeQuantity) { // if the quantity of the item in the cart is greater than the quantity to be removed
                    $_SESSION["shopping_cart"][$keys]["item_quantity"] -= $removeQuantity;
                    echo "<script>alert('Item Removed from Cart1')</script>";
                    echo "<script>window.location='cart.php'</script>";
                } elseif ($values["item_quantity"] == $removeQuantity) { // if the quantity of the item in the cart is equal to the quantity to be removed
                    unset($_SESSION["shopping_cart"][$keys]);
                    echo "<script>alert('Item Removed from Cart2')</script>";
                    echo "<script>window.location='cart.php'</script>";
                } else { // if the quantity of the item in the cart is less than the quantity to be removed
                    echo "<script>alert('???')</script>";
                    echo "<script>window.location='cart.php'</script>";
                }
            } else { // if the item is not in the cart
                echo "<script>alert('Item Not in Cart')</script>";
                echo "<script>window.location='cart.php'</script>";
            }
        }
    }
}
