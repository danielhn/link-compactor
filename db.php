<?php

require_once 'env.php';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

if (DATABASE_TYPE === 'sqlite') {
    $db = new PDO(SQLITE_DATABASE_PATH, null, null, $options);
} else if (DATABASE_TYPE === 'mysql') {
    $dsn = "mysql:host=" . MYSQL_DATABASE_HOST .
        ";dbname=" . MYSQL_DATABASE_NAME .
        ";charset=". MYSQL_DATABASE_CHARSET;
    $db = new PDO($dsn, MYSQL_DATABASE_USER, MYSQL_DATABASE_PASSWORD, $options);
} else if (DATABASE_TYPE === 'pgsql') {
    $dsn = "pgsql:host=" . PGSQL_DATABASE_HOST .
        ";port=" . PGSQL_DATABASE_PORT .
        ";dbname=" . PGSQL_DATABASE_NAME;
    $db = new PDO($dsn, PGSQL_DATABASE_USER, PGSQL_DATABASE_PASSWORD, $options);
} else {
    throw new Exception('Unrecognized database type. Available types: sqlite, mysql, pgsql.');
}

return $db;