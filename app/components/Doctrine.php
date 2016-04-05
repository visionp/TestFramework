<?php
/**
 * Created by PhpStorm.
 * User: Vision
 * Date: 11.02.2016
 * Time: 11:28
 */

namespace app\components;

use app\helpers\File;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Configuration;


class Doctrine extends ComponentBase
{
    public $driver = 'pdo_mysql';
    public $user;
    public $password;
    public $dbname;
    public $host;

    public $applicationMode = true;

    protected $path = MAIN_DIRECTORY . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'tables';
    protected $pathProxy;
    protected $pathCache = MAIN_DIRECTORY . DIRECTORY_SEPARATOR . 'cache';
    protected $isDevMode = true;
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    public $doctrine;

    public function init()
    {
        $dbParams = [
            'driver'   => $this->driver,
            'host' => $this->host,
            'user'     => $this->user,
            'password' => $this->password,
            'dbname'   => $this->dbname,
            'charset'  => 'utf8mb4'
        ];

        $this->pathProxy = $this->path . DIRECTORY_SEPARATOR . 'proxy';

        if(!is_dir($this->pathCache)){
            File::createDirectory($this->pathCache);
        }

        if(!is_dir($this->pathProxy)){
            File::createDirectory($this->pathProxy);
        }

        $config = new Configuration();
        $cache = new \Doctrine\Common\Cache\FilesystemCache($this->pathCache);
        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);
        $driverImpl = $config->newDefaultAnnotationDriver($this->path);
        $config->setMetadataDriverImpl($driverImpl);
        $config->setProxyDir($this->pathProxy);
        $config->setProxyNamespace('app\tables\proxy');

        if ($this->applicationMode == "development") {
            $config->setAutoGenerateProxyClasses(true);
        } else {
            $config->setAutoGenerateProxyClasses(false);
        }

        $this->doctrine = EntityManager::create($dbParams, $config);
    }

}