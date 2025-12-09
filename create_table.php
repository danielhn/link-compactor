<?php

require_once __DIR__ . '/config.php';
$db = require_once __DIR__ . '/db.php';

$sql = "CREATE TABLE IF NOT EXISTS ". DATABASE_TABLE_NAME . " 
        (id VARCHAR(" . SHORT_LINK_ID_LENGTH . ") PRIMARY KEY NOT NULL,
         url VARCHAR(" . URL_MAX_LENGTH . ") UNIQUE NOT NULL)";

$db->query($sql);