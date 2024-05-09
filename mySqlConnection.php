<?php

class mySqlConnection {
    private static $instance = null;
    private static $host = "host";
    private static $user = 'username';
    private static $pass = 'password';
    private static $dbname = 'db_name';
    private static $connection = null;

    private function __construct() {
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        if (!self::$connection) {
            try {
                self::$connection = new PDO("mysql:host=".self::$host.";dbname=".self::$dbname, self::$user, self::$pass);
                // Set PDO to throw exceptions on error
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (\Exception $e) {
                die("Failed to connect to MySQL: {$e->getMessage()}");
            }
        }
        return self::$connection;
    }

    public function close() {
        self::$connection = null;
        self::$instance = null;
    }
}
