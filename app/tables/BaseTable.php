<?php
/**
 * Created by PhpStorm.
 * User: Vision
 * Date: 11.02.2016
 * Time: 14:44
 */

namespace app\tables;


use app\Application;
use app\core\SimpleObject;
use app\helpers\Http;
use Monolog\Logger;

/**
 * Class BaseTable
 * @package app\doctrine
 */
abstract class BaseTable extends Object
{

    public function __construct($params = [])
    {

        $this->load($params);
        if(property_exists($this, 'created_at')){
            $this->created_at = time();
        }

    }


    public function load($params)
    {
        foreach($params as $p => $v){
            if(property_exists($this, $p)){
                $this->$p = $v;
            }
        }
    }


    public static function className()
    {
        return static::class;
    }


    public static function tableName()
    {
        throw new \Exception('Not set tableName in method tableName');
    }


    public static function getFromHttp(){
        return null;
    }


    /**
     * @return array
     */
    public static function getAll($lang = 'ru')
    {
        return static::orm()->findAll();
    }



    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    public static function orm()
    {
        return Application::app()->db->doctrine->getRepository(static::className());
    }


    public function save()
    {
        /**
         * @var $em \Doctrine\ORM\EntityManager
         */
        $em = Application::app()->db->doctrine;
        $em->persist($this);
        return $em->flush();
    }


    /**
     * @return mixed
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Exception
     */
    public static function update()
    {
        //Этот метод должен вернуть массив с данными которые требуется сохранить в таблице
        $insertData = static::getFromHttp();

        if(is_array($insertData) && count($insertData)){
            /**
             * @var $em \Doctrine\ORM\EntityManager
             */
            $em = Application::app()->db->doctrine;
            $connection = $em->getConnection();
            $connection->beginTransaction();
            $batchSize = 20;

            try{
                $table = static::tableName();
                $sql = "delete from $table where 1;";
                $sql .= "ALTER TABLE $table AUTO_INCREMENT = 1;";
                $connection->exec($sql);

                foreach($insertData as $i => $row){
                    $model = new static($row);
                    $em->persist($model);
                    if (($i % $batchSize) === 0) {
                        $em->flush();
                        $em->clear();
                    }
                }
                $em->flush();
                $em->clear();

                $connection->commit();
            } catch (\Exception $e) {
                $connection->rollBack();
                Application::app()->logger->addRecord(Logger::CRITICAL, $e->getMessage(), [
                    'exception' => $e,
                    'message' => 'No updated ' . static::tableName()
                ]);
            }

        }
        return true;

    }


    public static function getByServices($sql)
    {
        /**
         * @var $em \Doctrine\ORM\EntityManager
         */
        $em = Application::app()->db->doctrine;
        $items =  $em->getConnection()->fetchAll($sql);
        $itemsAll = [];
        foreach($items as $item){
            $itemsAll[$item['service_id']][] = $item;
        }
        return $itemsAll;
    }


}