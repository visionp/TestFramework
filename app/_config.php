<?php
/**
 * Created by PhpStorm.
 * User: VisioN
 * Date: 09.11.2015
 * Time: 21:08
 */
return [
    'components' => [
        'db_pdo' => [
            'class' => 'app\components\Pdo',
            'user' => 'root',
            'password' => '',
            'dbname' => ''
        ],
        'db' => [
            'class' => 'app\components\Doctrine',
            'host' => 'localhost',
            'user' => 'root',
            'password' => '',
            'dbname' => ''
        ],
    ]
];