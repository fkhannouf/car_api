<?php

namespace CarApi\Entities;

use GraphQL\Utils\Utils;

class Car
{

    public $make;
    public $model;
    public $trim;
    public $tco;
    public $year;

    public function __construct(array $data)
    {
        Utils::assign($this, $data);
    }
}