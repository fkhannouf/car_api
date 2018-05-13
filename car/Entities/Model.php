<?php

namespace CarApi\Entities;

use GraphQL\Utils\Utils;

class Model {

    public $name;

    public function __construct(array $data)
    {
        Utils::assign($this, $data);
    }
}