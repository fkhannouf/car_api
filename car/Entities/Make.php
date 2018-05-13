<?php

namespace CarApi\Entities;

use GraphQL\Utils\Utils;

class Make {

    public $name;

    public function __construct(array $data)
    {
        Utils::assign($this, $data);
    }
}