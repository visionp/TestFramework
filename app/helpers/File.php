<?php
/**
 * Created by PhpStorm.
 * User: Vision
 * Date: 10.02.2016
 * Time: 20:18
 */

namespace app\helpers;


class File
{
    public static function createDirectory($path, $mode = 0775, $recursive = true)
    {
        if (is_dir($path)) {
            return true;
        }
        $parentDir = dirname($path);
        if ($recursive && !is_dir($parentDir)) {
            static::createDirectory($parentDir, $mode, true);
        }
        try {
            $result = mkdir($path, $mode);
            chmod($path, $mode);
        } catch (\Exception $e) {
            throw new \Exception("Failed to create directory '$path': " . $e->getMessage(), $e->getCode(), $e);
        }

        return $result;
    }

}