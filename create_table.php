<?php

require_once __DIR__ . '/config.php';
$db = require_once __DIR__ . '/db.php';

$sql = "CREATE TABLE IF NOT EXISTS " . DATABASE_TABLE_NAME . " 
        (id CHAR(" . SHORT_LINK_ID_LENGTH . ") PRIMARY KEY NOT NULL,
         url VARCHAR(" . URL_MAX_LENGTH . ") NOT NULL)";

$db->query($sql);