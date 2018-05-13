<?php

namespace CarApi\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\Type;

use CarApi\Entities\DataSource;
use CarApi\Types\CustomTypes;

class QueryType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Query',
            'fields' => [
                'make' => [
                    'type' => CustomTypes::make(),
                    'description' => 'Returns manufacturer by id',
                    'args' => [
                        'id' => Type::int()
                    ]
                ],
                'makes' => [
                    'type' => new ListOfType(CustomTypes::make()),
                    'description' => 'Returns a list of manufacturers',
                    'args' => [
                        'startingWith' => Type::string()
                    ]
                ],
                'cars' => [
                    'type' => new ListOfType(CustomTypes::car()),
                    'description' => 'Returns car with given criteria',
                    'args' => [
                        'make' => Type::string(),
                        'tcoBelow' => Type::int(),
                        'tcoAbove' => Type::int()
                    ]
                ],

            ],
            'resolveField' => function($val, $args, $context, ResolveInfo $info) {
                return $this->{$info->fieldName}($val, $args, $context, $info);
            }
        ];
        parent::__construct($config);
    }

    public function make($rootValue, $args)
    {
        return DataSource::findMake($args['id']);
    }

    public function makes($rootValue, $args)
    {
        return DataSource::findMakes($args);
    }

    public function cars($rootValue, $args)
    {
        return DataSource::findCars($args);
    }

}