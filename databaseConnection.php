<?php
namespace DB;

class DBConnection {
    private $host = 'localhost';
    private $user = 'root';
    private $password = 'root';
    private $database = 'local';
    private $connection;

    public function dbConnect() {
        $this->connection = new \mysqli($this->host, $this->user, $this->password, $this->database);
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
        return $this->connection;
    }
}
