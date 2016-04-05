<?php
/**
 * Created by PhpStorm.
 * User: Vision
 * Date: 12.02.2016
 * Time: 16:49
 */

namespace app\components;


class PdoWrapper extends ComponentBase
{
    public $driver = 'pdo_mysql';
    public $host   = 'localhost';
    public $user;
    public $password;
    public $dbname;

    public $connection;

    public function init()
    {
        $this->connection = new \PDO('mysql:host=localhost;dbname=' . $this->dbname, $this->user, $this->password);
    }
}