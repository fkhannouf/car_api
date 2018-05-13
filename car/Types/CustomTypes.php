<?php

namespace CarApi\Types;

use GraphQL\Examples\Blog\Type\CommentType;
//use GraphQL\Examples\Blog\Type\Enum\ContentFormatEnum;
//use GraphQL\Examples\Blog\Type\Enum\ImageSizeEnumType;
//use GraphQL\Examples\Blog\Type\Field\HtmlField;
//use GraphQL\Examples\Blog\Type\SearchResultType;
//use GraphQL\Examples\Blog\Type\NodeType;
//use GraphQL\Examples\Blog\Type\QueryType;
//use GraphQL\Examples\Blog\Type\Scalar\EmailType;
//use GraphQL\Examples\Blog\Type\StoryType;
//use GraphQL\Examples\Blog\Type\Scalar\UrlType;
//use GraphQL\Examples\Blog\Type\UserType;
//use GraphQL\Examples\Blog\Type\ImageType;
//use GraphQL\Type\Definition\NonNull;
//use GraphQL\Type\Definition\Type;

use CarApi\Types\MakeType;

class CustomTypes
{
    // Object types:
    private static $make;
    private static $car;
    private static $query;

    public static function make()
    {
        return self::$make?: (self::$make = new MakeType());
    }

    public static function car()
    {
        return self::$car?: (self::$car = new CarType());
    }

    public static function query()
    {
        return self::$query ?: (self::$query = new QueryType());
    }
}

