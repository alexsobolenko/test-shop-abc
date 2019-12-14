<?php

namespace App;

abstract class Controller
{

    /** @var PDO */
    private $db;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $config = require __DIR__."../../config/database.php";

        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8";
        $this->db = new \PDO($dsn, $config['username'], $config['password'], []);
    }

    /**
     * @param $query
     * @return bool|\PDOStatement
     */
    public function query($query): ?\PDOStatement
    {
        return $this->db->query($query);
    }
}

