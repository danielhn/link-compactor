<?php

require_once 'env.php';

$options = [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

if (DATABASE_TYPE === 'sqlite') {
    $db = new PDO(SQLITE_DATABASE_PATH, null, null, $options);
} else if (DATABASE_TYPE === 'mysql') {
    $dsn = "mysql:host=" . MYSQL_DATABASE_HOST .
        ";dbname=" . MYSQL_DATABASE_NAME .
        ";charset=". MYSQL_DATABASE_CHARSET;
    $db = new PDO($dsn, MYSQL_DATABASE_USER, MYSQL_DATABASE_PASSWORD, $options);
} elseif (DATABASE_TYPE === 'pgsql') {
    $dsn = "pgsql:host=" . PGSQL_DATABASE_HOST .
        ";port=" . PGSQL_DATABASE_PORT .
        ";dbname=" . PGSQL_DATABASE_NAME;
    $db = new PDO($dsn, PGSQL_DATABASE_USER, PGSQL_DATABASE_PASSWORD, $options);
}

return $db;