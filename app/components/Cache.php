<?php
/**
 * Created by PhpStorm.
 * User: Vision
 * Date: 15.02.2016
 * Time: 17:15
 */

namespace app\components;

use Doctrine\Common\Cache\FilesystemCache;

class Cache extends FilesystemCache
{

    protected $path = MAIN_DIRECTORY . DIRECTORY_SEPARATOR . 'cache';

    public function __construct()
    {
        parent::__construct($this->path);
    }

    public function set($key, $data, $time = 0)
    {
        return $this->doSave($key, $data, $time);
    }


    public function get($key)
    {
        return $this->doFetch($key);
    }
}