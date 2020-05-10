<?php

namespace App;

/**
 * Class ErrorController
 * @package App
 */
class ErrorController extends Controller
{
    public function index() {
        $data = [
            'title'         => 'Page not found',
            'includeScript' => ['get_cart_count', 'get_account'],
        ];
        (new View())->generate('error', $data);
    }
}
