<?php

namespace App;

class View
{

    /**
     * @param       $contentView
     * @param array $data
     */
    public function generate($contentView, $data = [])
    {
        extract($data);
        include __DIR__.'/../template/default.php';
    }
}
