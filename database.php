<?php
// database.php - Տվյալների բազա
require_once 'config.php';

class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            if ($this->connection->connect_error) {
                throw new Exception("Connection failed: " . $this->connection->connect_error);
            }
            
            $this->connection->set_charset("utf8mb4");
            
        } catch (Exception $e) {
            die("Database error: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    // Օգտատերերի ֆունկցիաներ
    public function registerUser($username, $email, $password) {
        $conn = $this->getConnection();
        
        // Ստուգել արդյոք գոյություն ունի
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            return ["success" => false, "message" => "Օգտանունը կամ էլ.փոստը արդեն գոյություն ունի"];
        }
        
        // Գրանցել
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        
        if ($stmt->execute()) {
            return ["success" => true, "user_id" => $conn->insert_id];
        }
        
        return ["success" => false, "message" => "Սխալ գրանցման ժամանակ"];
    }
    
    public function loginUser($username, $password) {
        $conn = $this->getConnection();
        
        $stmt = $conn->prepare("SELECT id, username, email, password FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return ["success" => false, "message" => "Օգտատերը չի գտնվել"];
        }
        
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            return [
                "success" => true,
                "user" => [
                    "id" => $user['id'],
                    "username" => $user['username'],
                    "email" => $user['email']
                ]
            ];
        }
        
        return ["success" => false, "message" => "Սխալ գաղտնաբառ"];
    }
    
    // Նորությունների ֆունկցիաներ
    public function getAllNews() {
        $conn = $this->getConnection();
        
        $sql = "SELECT n.*, u.username as author_name 
                FROM news n 
                JOIN users u ON n.author_id = u.id 
                ORDER BY n.created_at DESC";
        
        $result = $conn->query($sql);
        $news = [];
        
        while ($row = $result->fetch_assoc()) {
            $news[] = $row;
        }
        
        return $news;
    }
    
    public function addNews($title, $content, $author_id) {
        $conn = $this->getConnection();
        
        $stmt = $conn->prepare("INSERT INTO news (title, content, author_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $title, $content, $author_id);
        
        return $stmt->execute();
    }
}
?>