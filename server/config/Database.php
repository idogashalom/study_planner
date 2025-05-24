<?php

namespace Config;

use PDO;
use PDOException;

class Database
{
    private $host = 'localhost';
    private $dbname = 'study_planner';
    private $user = 'root';
    private $pass = '';
    private $conn;

    public function getConnection()
    {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->user, $this->pass);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }

        return $this->conn;
    }
}
