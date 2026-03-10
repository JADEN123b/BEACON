<?php
// Simple MySQLi Database Connection for BEACON platform

class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "beacon_db";
    public $conn;

    // Get database connection
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
            
            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }
            
            // Set charset
            $this->conn->set_charset("utf8mb4");
            
        } catch(Exception $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
    
    // Execute query and return result
    public function query($sql) {
        $result = $this->conn->query($sql);
        if (!$result) {
            throw new Exception("Query failed: " . $this->conn->error);
        }
        return $result;
    }
    
    // Escape string to prevent SQL injection
    public function escape($string) {
        return $this->conn->real_escape_string($string);
    }
    
    // Get last insert ID
    public function getLastInsertId() {
        return $this->conn->insert_id;
    }
    
    // Close connection
    public function close() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}

// Simple database functions
function getDbConnection() {
    $database = new Database();
    return $database->getConnection();
}
?>
