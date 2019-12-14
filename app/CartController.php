<?php

namespace App;

use PDO;

class CartController extends Controller
{

    /**
     *  Show cart
     */
    public function index(): void
    {
        session_start();
        $cart = [];
        $count = 0;
        $sum = 0;
        $message = "Cart is empty";

        // when the purchase was paid
        if (isset($_SESSION["purchase"])) {
            $message = "Thank you for your purchase $".$_SESSION['purchase'].". Your account balance: $".$_SESSION['account'].".";
            unset($_SESSION["purchase"]);
        }

        // when the cart is not empty
        if (isset($_SESSION["cart"])) {
            $cart = $_SESSION["cart"];
            $count = $_SESSION["count"] ?? 0;
            $sum = $_SESSION["sum"] ?? 0;
        }

        $data = [
            "title" => "Cart",
            "cart" => $cart,
            "count" => $count,
            "sum" => $sum,
            "message" => $message,
            "includeScript" => ["get_cart_count", "get_account", "cart"]
        ];
        (new View())->generate("cart", $data);
    }

    /**
     * Checking POST data
     *
     * @return bool
     */
    private function checkPostData(): bool
    {
        if (!count($_POST) == 0) {
            session_start();
            return true;
        }
        return false;
    }

    /**
     *  Add product(s) to cart
     */
    public function add()
    {
        if ($this->checkPostData()) {
            $id = $_POST["id"];
            $quantity = $_POST["quantity"];
            $product = $this->query("SELECT p.* FROM product p WHERE p.id = $id");
            $product = $product->fetch(PDO::FETCH_ASSOC);

            // add product to cart
            if (isset($_SESSION["cart"][$product["id"]])) {
                $_SESSION["cart"][$product["id"]]["count"] += $quantity;
                $_SESSION["cart"][$product["id"]]["sum"] += $product["price"]*$quantity;
            } else {
                $_SESSION["cart"][$product["id"]] = $product;
                $_SESSION["cart"][$product["id"]]["count"] = $quantity;
                $_SESSION["cart"][$product["id"]]["sum"] = $product["price"]*$quantity;
                $_SESSION["cart"][$product["id"]]["price"] = $product["price"];
            }

            // increase counter
            if (isset($_SESSION["count"])) {
                $_SESSION["count"] += $quantity;
            } else {
                $_SESSION["count"] = $quantity;
            }

            // increase sum
            if (isset($_SESSION["sum"])) {
                $_SESSION["sum"] += $product["price"]*$quantity;
            } else {
                $_SESSION["sum"] = $product["price"] * $quantity;
            }

            // return counter
            echo json_encode([
                "count" => $_SESSION["count"] ?? 0
            ]);
        }
    }

    /**
     *  Remove product(s) from cart
     */
    public function remove()
    {
        if ($this->checkPostData()) {

            // check product in cart
            if (isset($_SESSION["cart"][$_POST["id"]])) {
                $product = $_SESSION["cart"][$_POST["id"]];

                // remove product from cart
                $_SESSION["cart"][$_POST["id"]]["count"] -= $_POST["quantity"];
                $_SESSION["cart"][$_POST["id"]]["sum"] -= $_POST["quantity"]*$product["price"];
                if ($_SESSION["cart"][$_POST["id"]]["count"] <= 0) {
                    unset($_SESSION["cart"][$_POST["id"]]);
                }

                // decrease counter
                $_SESSION["count"] -= $_POST["quantity"];
                if ($_SESSION["count"] <= 0) {
                    unset($_SESSION["count"]);
                }

                // decrease sum
                $_SESSION["sum"] -= $product["price"]*$_POST["quantity"];
                if ($_SESSION["sum"] <= 0) {
                    unset($_SESSION["sum"]);
                }
            }

            // return counter and sum
            echo json_encode([
                "count" => $_SESSION["count"] ?? 0,
                "sum" => $_SESSION["sum"] ?? 0,
                "productSum" => $_SESSION["cart"][$_POST["id"]] ?? 0
            ]);
        }
    }

    /**
     *  Purchase for all product from cart
     */
    public function pay()
    {
        if ($this->checkPostData()) {
            $status = "failed";
            $sum = $_SESSION["sum"] + $_POST["transport"];

            // checking the availability of the required amount on the account
            if ($sum <= $_SESSION["account"]) {
                $_SESSION["purchase"] = $sum;
                $_SESSION["account"] -= $sum;
                unset($_SESSION["cart"]);
                unset($_SESSION["count"]);
                unset($_SESSION["sum"]);
                $status = "success";
            }

            // return "success" or "failed"
            echo json_encode([
                "status" => $status
            ]);
        }
    }

    /**
     *  Return number of products in cart
     */
    public function count()
    {
        if ($this->checkPostData()) {
            echo json_encode([
                "count" => $_SESSION["count"] ?? 0
            ]);
        }
    }

    /**
     *
     */
    public function clear()
    {
        if (isset($_SESSION["cart"])) {
            unset($_SESSION["cart"]);
            echo "ok";
        } else {
            echo "not isset";
        }

        if (isset($_SESSION["count"])) {
            unset($_SESSION["count"]);
            echo "ok";
        } else {
            echo "not isset";
        }

        if (isset($_SESSION["sum"])) {
            unset($_SESSION["sum"]);
            echo "ok";
        } else {
            echo "not isset";
        }

        $_SESSION["account"] = 100;
    }
}
