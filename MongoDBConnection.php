<?php
namespace Api;
require __DIR__ . '/vendor/autoload.php';

use \MongoDB\Client;

class MongoDBConnection
{

    private  $connectionString1  = "mongodb+srv://ritick2000rai:KliTzKZBVEExofAC@careermarg.poz3p4x.mongodb.net/home";

    // MongoDB connection object
    private $connection = null;

    // Singleton instance
    private static $instance = null;

    // Constructor is private to prevent object creation
    private function __construct()
    {
    }

    // Get the singleton instance
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new MongoDBConnection();
        }

        return self::$instance;
    }

    // Connect to MongoDB
    public function connect()
    {
        if (!$this->connection) {
            try {
                $this->connection =  new \MongoDB\Client($this->connectionString1);
            } catch (\Exception $e) {
                die("Failed to connect to MongoDB: {$e->getMessage()}");
            }
        }
        return $this->connection;
    }
}
