<?php
/**
 * Created by PhpStorm.
 * User: VisioN
 * Date: 10.11.2015
 * Time: 13:06
 */

namespace app\models;


class StadiumModel extends BaseModel {

    public $green_sector = [
        1 => [
            1 => ['status' => 5],
            2 => ['status' => 0],
            3 => ['status' => 0],
            4 => ['status' => 0]
        ],
        2 => [
            1 => ['status' => 0],
            2 => ['status' => 0],
            3 => ['status' => 0],
            4 => ['status' => 0]
        ]
    ];
    public $yellow_sector = [
        1 => [
            1 => ['status' => 0],
            2 => ['status' => 0],
            3 => ['status' => 0],
            4 => ['status' => 0]
        ],
        2 => [
            1 => ['status' => 0],
            2 => ['status' => 0],
            3 => ['status' => 0],
            4 => ['status' => 0]
        ]
    ];
}