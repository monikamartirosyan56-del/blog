<?php
// add_news.php - Նորություն ավելացնել
require_once 'config.php';
require_once 'database.php';

// Ստուգել արդյոք մուտք է գործել
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$db = Database::getInstance();
$user = getUserInfo();

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    
    if (empty($title) || empty($content)) {
        $message = "Խնդրում ենք լրացնել բոլոր դաշտերը";
        $message_type = "error";
    } elseif (strlen($title) < 5) {
        $message = "Վերնագիրը պետք է լինի առնվազն 5 նիշ";
        $message_type = "error";
    } elseif (strlen($content) < 10) {
        $message = "Բովանդակությունը պետք է լինի առնվազն 10 նիշ";
        $message_type = "error";
    } else {
        $success = $db->addNews($title, $content, $user['id']);
        
        if ($success) {
            $message = "Նորությունը հաջողությամբ ավելացվել է";
            $message_type = "success";
            
            // Մաքրել ֆորմը
            $_POST = [];
            
            // Վերահղում 2 վայրկյան հետո
            echo '<meta http-equiv="refresh" content="2;url=index.php">';
        } else {
            $message = "Սխալ նորություն ավելացնելիս";
            $message_type = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <title>Ավելացնել նորություն</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>➕ Ավելացնել նորություն</h1>
            <a href="index.php" class="btn">Վերադառնալ</a>
        </div>
    </div>
    
    <div class="container">
        <div class="form-container">
            <h2>Ստեղծել նոր նորություն</h2>
            
            <?php if ($message): ?>
                <div class="message <?php echo $message_type; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="title">Վերնագիր</label>
                    <input type="text" class="form-control" id="title" name="title" 
                           value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>"
                           required placeholder="Մուտքագրեք վերնագիր">
                </div>
                
                <div class="form-group">
                    <label for="content">Բովանդակություն</label>
                    <textarea class="form-control" id="content" name="content" rows="10" 
                              required placeholder="Մուտքագրեք նորության բովանդակություն"><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
                </div>
                
                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">Հրապարակել</button>
                    <a href="index.php" class="btn">Չեղարկել</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>