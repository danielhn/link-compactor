<?php

require_once 'env.php';

$options = [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

$db = new PDO(SQLITE_DATABASE_PATH,null,null , $options);

return $db;