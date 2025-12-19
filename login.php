<?php
// login.php - Մուտքի էջ
require_once 'config.php';
require_once 'database.php';

// Եթե արդեն մուտք է գործել, ուղղորդել գլխավոր էջ
if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $message = "Խնդրում ենք լրացնել բոլոր դաշտերը";
        $message_type = "error";
    } else {
        $db = Database::getInstance();
        $result = $db->loginUser($username, $password);
        
        if ($result['success']) {
            $_SESSION['user_id'] = $result['user']['id'];
            $_SESSION['username'] = $result['user']['username'];
            $_SESSION['email'] = $result['user']['email'];
            
            header('Location: index.php');
            exit();
        } else {
            $message = $result['message'];
            $message_type = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <title>Մուտք</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>🔐 Մուտք</h1>
            <a href="index.php" class="btn">Վերադառնալ</a>
        </div>
    </div>
    
    <div class="container">
        <div class="form-container">
            <h2>Մուտք գործել Ձեր հաշիվ</h2>
            
            <?php if ($message): ?>
                <div class="message <?php echo $message_type; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Օգտանուն կամ Էլ. փոստ</label>
                    <input type="text" class="form-control" id="username" name="username" 
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                           required>
                </div>
                
                <div class="form-group">
                    <label for="password">Գաղտնաբառ</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">Մուտք</button>
            </form>
            
            <div style="text-align: center; margin-top: 20px;">
                <p>Դեռ հաշիվ չունե՞ք։ <a href="register.php">Գրանցվել</a></p>
            </div>
        </div>
    </div>
</body>
</html>