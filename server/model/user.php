<?php

// Define the namespace for organization and autoloading
namespace Model;

// Import the PDO classes we'll use to interact with the database
use PDO;
use PDOException;

// This class handles all user-related actions like registering and checking emails
class User
{
    // This variable will store the database connection
    private $conn;

    // This variable holds the name of the database table where users are stored
    private $table = 'users'; 

    // The constructor is a special method that runs when we create a new instance of this class
    // It takes a PDO database connection object and stores it in $this->conn
    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    // This function checks if a user's email already exists in the database
    public function emailExists($email)
    {
        // Prepare a SQL query to search for a user with the given email
        $sql = "SELECT id FROM {$this->table} WHERE email = :email LIMIT 1";

        // Prepare the SQL statement (to prevent SQL injection)
        $stmt = $this->conn->prepare($sql);

        // Execute the query by passing the actual email value
        $stmt->execute(['email' => $email]);

        // Check if any result was returned. If yes, email exists; if not, it doesn't
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    // This function registers a new user in the database
    public function register($name, $email, $password)
    {
        // Secure the password using PHP's password_hash function (never store plain text passwords!)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert a new user into the "users" table
        $sql = "INSERT INTO {$this->table} (name, email, password) VALUES (:name, :email, :password)";

        // Prepare the SQL statement
        $stmt = $this->conn->prepare($sql);

        // Execute the statement with the actual values for name, email, and hashed password
        return $stmt->execute([
            'name'     => $name,
            'email'    => $email,
            'password' => $hashedPassword
        ]);
    }

    // This function gets a user's full information from the database using their email
    public function getUserByEmail($email)
    {
        try {
            // SQL query to find a user with the provided email
            $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";

            // Prepare the SQL statement
            $stmt = $this->conn->prepare($sql);

            // Execute the query with the actual email
            $stmt->execute(['email' => $email]);

            // Return the user data as an associative array (key => value)
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // If an error occurs while accessing the database, throw a custom exception with a message
            throw new \Exception("Database Error: " . $e->getMessage(), (int)$e->getCode(), $e);
        }
    }
}
