<?php
/**
 * Created by PhpStorm.
 * User: VisioN
 * Date: 09.11.2015
 * Time: 21:31
 */

namespace app\components;


class Redis extends \Redis {

    public $host = '127.0.0.1';
    public $port = 6379;
    public $timeout;
    public $login = 'root';


    public function __construct()
    {
        parent::__construct();
        $this->connect($this->host, $this->port);
        $this->auth($this->login);
    }



    public function connect( $host, $port = 6379, $timeout = 0.0 )
    {
        parent::connect( $host, $port, $timeout);
    }

}