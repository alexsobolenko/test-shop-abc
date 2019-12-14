<?php

namespace App;

class ErrorController extends Controller
{

    /**
     *  Show error
     */
    public function index() {
        $data = [
            'title' => 'Page not found',
            'includeScript' => ['get_cart_count', 'get_account']
        ];
        (new View())->generate('error', $data);
    }
}
