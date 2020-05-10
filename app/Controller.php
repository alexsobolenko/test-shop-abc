<?php

namespace App;

use PDO;
use PDOStatement;

/**
 * Class Controller
 * @package App
 */
abstract class Controller
{
    /** @var PDO */
    private $db;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $config = require sprintf("%s/../../config/database.php", __DIR__);
        $dsn = sprintf("mysql:host=%s;dbname=%s;charset=utf8", $config['host'], $config['dbname']);
        $this->db = new \PDO($dsn, $config['username'], $config['password'], []);
    }

    /**
     * @param $query
     * @return PDOStatement|bool|null
     */
    public function query($query)
    {
        return $this->db->query($query);
    }
}

