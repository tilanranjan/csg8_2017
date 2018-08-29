<?php

/**
 * Handling database connection
 * @author Proleadsoft dev Team
 */
class DbConnect {

    private $conn;
    function __construct() {        
    }

    /**
     * Establishing database connection
     * @return database connection handler
     */
    function connect() {
        include_once dirname(__FILE__) . '/../config.php';
        // Connecting to mysql database
        $this->conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);

        // Check for database connection error
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        // returing connection resource
        return $this->conn;
    }

    function beginTransaction() {
        $this->conn->begin_transaction();
    }

    function commit() {
        $this->conn->commit();
    }

    function rollback() {
        $this->conn->rollBack();
    }
}

?>   