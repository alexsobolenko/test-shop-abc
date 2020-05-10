<?php

namespace App;

/**
 * Class View
 * @package App
 */
class View
{
    /**
     * @param $contentView
     * @param array $data
     */
    public function generate($contentView, $data = [])
    {
        extract($data);
        include sprintf("%s/../template/default.php", __DIR__);
    }
}
