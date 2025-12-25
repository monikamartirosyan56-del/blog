<?php
class Database {
    private $host = "localhost";
    private $db = "shop";
    private $user = "root";
    private $pass = "";
    public $conn;

    public function connect() {
        $this->conn = new mysqli(
            $this->host,
            $this->user,
            $this->pass,
            $this->db
        );
        return $this->conn;
    }
}
?>