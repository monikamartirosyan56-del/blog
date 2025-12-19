<?php
// register.php - Գրանցման էջ
require_once 'config.php';
require_once 'database.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($username)  empty($email)  empty($password)) {
        $message = "Խնդրում ենք լրացնել բոլոր դաշտերը";
        $message_type = "error";
    } elseif ($password !== $confirm_password) {
        $message = "Գաղտնաբառերը չեն համապատասխանում";
        $message_type = "error";
    } else {
        $db = Database::getInstance();
        $result = $db->registerUser($username, $email, $password);
        
        if ($result['success']) {
            // Ավտոմատ մուտք գործել գրանցվելուց հետո
            $login_result = $db->loginUser($username, $password);
            
            if ($login_result['success']) {
                $_SESSION['user_id'] = $login_result['user']['id'];
                $_SESSION['username'] = $login_result['user']['username'];
                $_SESSION['email'] = $login_result['user']['email'];
                
                header('Location: index.php');
                exit();
            }
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
    <title>Գրանցում</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>📝 Գրանցում</h1>
            <a href="index.php" class="btn">Վերադառնալ</a>
        </div>
    </div>
    
    <div class="container">
        <div class="form-container">
            <h2>Ստեղծել նոր հաշիվ</h2>
            
            <?php if ($message): ?>
                <div class="message <?php echo $message_type; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Օգտանուն</label>
                    <input type="text" class="form-control" id="username" name="username" 
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                           required>
                </div>
                
                <div class="form-group">
                    <label for="email">Էլ. փոստ</label>
                    <input type="email" class="form-control" id="email" name="email"
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                           required>
                </div>
                
                <div class="form-group">
                    <label for="password">Գաղտնաբառ</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Հաստատել գաղտնաբառը</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">Գրանցվել</button>
            </form>
            
            <div style="text-align: center; margin-top: 20px;">
                <p>Արդեն հաշիվ ունե՞ք։ <a href="login.php">Մուտք գործել</a></p>
            </div>
        </div>
    </div>
</body>
</html>