<?php 
class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        // Include the config.php file and get the configuration array
        $config = require_once __DIR__ . '/config.php';

        // Create the DSN string for PDO connection
        $dsn = "mysql:host={$config['localhost']};dbname={$config['project_management']};charset=utf8mb4";
        
        // PDO connection options
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            // Establish the PDO connection using credentials from config
            $this->connection = new PDO($dsn, $config['db_user'], $config['db_pass'], $options);
        } catch (\PDOException $e) {
            // Handle connection error
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public static function getInstance() {
        // Create the instance if it doesn't exist yet
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        // Return the PDO connection
        return $this->connection; 
    }
}