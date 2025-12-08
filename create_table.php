<?php

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/config.php';

$database = new PDO(SQLITE_DATABASE_PATH);

$sql = "CREATE TABLE IF NOT EXISTS linkshortener 
        (id VARCHAR(" . SHORT_LINK_ID_LENGTH . ") PRIMARY KEY NOT NULL,
         url VARCHAR(" . URL_MAX_LENGTH . ") UNIQUE NOT NULL)";

$database->query($sql);