<?php

// Application configuration

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'project_management');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');

// Other configurations
define('APP_NAME', 'Project Management');
define('APP_URL', 'http://localhost/tawba-zehaf_project');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include other necessary files
require_once __DIR__ . '/../src/Helpers/functions.php';

