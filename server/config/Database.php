<?php

namespace Config;

use PDO;
use PDOException;

class Database
{
    // Database connection details
    private $host = 'localhost';        // Server name
    private $dbname = 'study_planner';  // Database name
    private $user = 'root';             // MySQL username
    private $pass = '';                 // MySQL password (empty by default in local XAMPP)
    
    private $conn; // This will hold the PDO connection object

    // Public method to get the database connection
    public function getConnection()
    {
        // If the connection has not been created yet
        if ($this->conn === null) {
            try {
                // Create a new PDO connection
                $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->user, $this->pass);
                
                // Enable exceptions for error handling
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                // Stop execution and show error if connection fails
                die("Database connection failed: " . $e->getMessage());
            }
        }

        // Return the PDO connection object
        return $this->conn;
    }
}
