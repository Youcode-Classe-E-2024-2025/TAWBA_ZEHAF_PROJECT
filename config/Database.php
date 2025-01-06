<?php 
class Database {
private static $instance = null;
private $conn;

private function __construct() {
// Include the config.php file and store the returned array in $config
$config = require_once __DIR__ . '/config.php';

// Now you can use the $config array for the database connection
$dsn = "mysql:host={$config['localhost']};dbname={$config['project_management']};charset=utf8mb4";
$options = [
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
PDO::ATTR_EMULATE_PREPARES => false,
];

try {
// Establish the PDO connection using credentials from config
$this->conn = new PDO($dsn, $config['db_user'], $config['db_pass'], $options);
} catch (\PDOException $e) {
throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
}

public static function getInstance() {
if (self::$instance == null) {
self::$instance = new Database();
}
return self::$instance;
}

public function getConnection() {
return $this->conn;
}
}