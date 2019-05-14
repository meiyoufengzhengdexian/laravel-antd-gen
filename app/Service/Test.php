<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19-5-4
 * Time: 下午8:31
 */

namespace App\Service;


class Test
{
    public $id = 20;
    public $name = 20;
    public $phone = 20;

    public function toArray()
    {
        return new \stdClass();
    }
}