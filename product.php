<?php
class Product {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function add($name, $price) {
        $sql = "INSERT INTO products (name, price) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$name, $price]);
    }

    public function all() {
        return $this->conn->query("SELECT * FROM products");
    }
}