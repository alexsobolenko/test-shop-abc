<?php

namespace App;

class HomeController extends Controller
{

    /**
     *  Show main page
     */
    public function index()
    {
        $data = [
            'title' => 'Home',
            'includeScript' => ['get_cart_count', 'get_account', 'home']
        ];
        (new View())->generate('home', $data);
    }

    /**
     *  Get list of products
     */
    public function get()
    {
        $products = $this->query("SELECT p.*,
            IFNULL((SELECT SUM(rs.rating) FROM rating rs WHERE rs.product_id = p.id), 0) AS `sum`,
            (SELECT COUNT(rc.rating) FROM rating rc WHERE rc.product_id = p.id) AS `count`
            FROM product p");
        $products = $products->fetchAll(\PDO::FETCH_ASSOC);

        // calculate rating
        for ($i = 0, $len = count($products); $i < $len; $i++) {
            $products[$i]['rating'] = $products[$i]['count'] == 0 ? 0 : ceil(100*$products[$i]['sum']/$products[$i]['count'])/100;
        }

        echo json_encode([
            'products' => $products
        ]);
    }

    /**
     *  Return account
     */
    public function account() {
        if (!count($_POST) == 0) {
            session_start();

            if (!isset($_SESSION['account'])) {
                $_SESSION['account'] = 100;
            }

            echo json_encode([
                'account' => round(100*$_SESSION['account'])/100
            ]);
        }
    }

    /**
     *  Set rating to product
     */
    public function rating() {
        if (!count($_POST) == 0) {
            session_start();

            extract($_POST);
            $status = 'success';

            // rating can be put only once
            if (isset($_SESSION['rating'])) {
                if (in_array($id, $_SESSION['rating'])) {
                    $status = 'failed';
                }
            }

            if ($status === 'success') {
                $this->query("INSERT INTO rating (product_id, rating) VALUES ($id, $rating)");
                $_SESSION['rating'][] = $id;
            }

            // get updated rating
            $rating = $this->query("SELECT SUM(r.rating) AS `sum`, COUNT(r.id) AS `count` FROM rating r WHERE r.product_id = $id");
            $rating = $rating->fetch(\PDO::FETCH_ASSOC);

            echo json_encode([
                'status' => $status,
                'rating' => ceil(100*$rating['sum']/$rating['count'])/100,
                'count' => $rating['count']
            ]);
        }
    }
}
