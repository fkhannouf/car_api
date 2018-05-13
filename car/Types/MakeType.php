<?php
/**
 * Created by PhpStorm.
 * User: frederic-khannouf
 * Date: 13.05.18
 * Time: 00:39
 */

namespace CarApi\Types;

use CarApi\Entities\Make;
use GraphQL\Type\Definition\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;



class MakeType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Make',
            'description' => 'Cars manufacturer',
            'fields' => function() {
                return [
                    'name' => Type::string()
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