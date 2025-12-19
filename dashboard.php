<?php
// dashboard.php - Օգտատիրոջ պահպանում
require_once 'config.php';
require_once 'database.php';

// Ստուգել արդյոք մուտք է գործել
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$db = Database::getInstance();
$user = getUserInfo();
?>
<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <title>Պահպանում</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>👋 Բարի գալուստ, <?php echo htmlspecialchars($user['username']); ?>!</h1>
            <div class="nav">
                <a href="index.php" class="btn">Գլխավոր</a>
                <a href="add_news.php" class="btn btn-primary">➕ Ավելացնել նորություն</a>
                <a href="logout.php" class="btn btn-danger">Ելք</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="user-info">
            <h2>Ձեր տվյալները</h2>
            <div class="info-card">
                <p><strong>Օգտանուն:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                <p><strong>Էլ. փոստ:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            </div>
        </div>
        
        <div class="actions">
            <h2>Արագ գործողություններ</h2>
            <div class="action-buttons">
                <a href="add_news.php" class="btn btn-primary">Ավելացնել նորություն</a>
                <a href="index.php" class="btn">Դիտել բոլոր նորությունները</a>
            </div>
        </div>
    </div>
</body>
</html>