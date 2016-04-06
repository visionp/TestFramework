<?php
/**
 * Created by PhpStorm.
 * User: VisioN
 * Date: 09.11.2015
 * Time: 21:08
 */

return [
    'components' => [
        'db' => [
            'class' => 'app\components\Doctrine',
            'host' => 'localhost',
            'user' => 'root',
            'password' => '',
            'dbname' => ''
        ],
        'cache' => [
            'class' => 'app\components\Cache'
        ]
    ]
];