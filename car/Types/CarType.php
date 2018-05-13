<?php

namespace CarApi\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;



class CarType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Car',
            'description' => 'Car description',
            'fields' => function() {
                return [
                    'make' => Type::string(),
                    'model' => Type::string(),
                    'trim' => Type::string(),
                    'tco' => Type::int(),
                    'year' => Type::int()
                ];
            },
            'resolveField' => function($value, $args, $context, ResolveInfo $info) {
                $method = 'resolve' . ucfirst($info->fieldName);
                if (method_exists($this, $method)) {
                    return $this->{$method}($value, $args, $context, $info);
                } else {
                    return $value->{$info->fieldName};
                }
            }
        ];
        parent::__construct($config);
    }
}