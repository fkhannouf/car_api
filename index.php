<?php

require_once __DIR__ . '/vendor/autoload.php';

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use GraphQL\Server\StandardServer;

use CarApi\Types\QueryType;
use CarApi\Entities\DataSource;


try {
    DataSource::init();

    $queryType = new QueryType();

    $schema = new Schema([
        'query' => $queryType
    ]);

    $server = new StandardServer([
        'schema' => $schema
    ]);

    $server->handleRequest();
} catch (\Exception $e) {
    StandardServer::send500Error($e);
}