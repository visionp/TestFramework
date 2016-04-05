<?php
/**
 * Created by PhpStorm.
 * User: Vision
 * Date: 20.02.2016
 * Time: 16:17
 */

namespace app\commands;


use app\ApplicationConsole;
use Doctrine\DBAL\Tools\Console\ConsoleRunner;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Symfony\Component\Console\Helper\HelperSet;

class ControllerDoctrine
{

    public function actionIndex()
    {
        /**
         * @var $em \Doctrine\ORM\EntityManager
         */
        $em = ApplicationConsole::app()->db->doctrine;

        $helperSet = new HelperSet([
            'db' => new ConnectionHelper($em->getConnection()),
            'em' => new EntityManagerHelper($em)
        ]);

        //unset($_SERVER['argv'][0]);
        unset($_SERVER['argv'][1]);
        $_SERVER['argv'] = array_values($_SERVER['argv']);

        \Doctrine\ORM\Tools\Console\ConsoleRunner::run($helperSet);
    }

}