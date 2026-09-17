<?php declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../utils/apiUtils.php';

function getDb() : PDO {
    static $db = NULL;
    if ($db == NULL) {
        $db = createConnection(DB_NAME, DB_USER, DB_PASSWORD);
    }
    return $db;
}

function createConnection($dbname, $username, $passwd) {
    try {
        return new PDO("mysql:host=" . DB_HOST . ";dbname=" . $dbname, $username, $passwd, array (
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ));
    } catch (PDOException $e) {
        jsonResponse([KEY_MESSAGE => $e->getMessage()], 500);
    }
}