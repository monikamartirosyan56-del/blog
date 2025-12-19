<?php
// index.php - Գլխավոր էջ
require_once 'config.php';
require_once 'database.php';

$db = Database::getInstance();
$news = $db->getAllNews();
$user = getUserInfo();
?>
<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Նորությունների Կայք</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>📰 Նորությունների Կայք</h1>
            <div class="nav">
                <?php if (isLoggedIn()): ?>
                    <span>Բարև, <?php echo htmlspecialchars($user['username']); ?>!</span>
                    <a href="dashboard.php" class="btn">Պահպանում</a>
                    <a href="logout.php" class="btn btn-danger">Ելք</a>
                <?php else: ?>
                    <a href="login.php" class="btn">Մուտք</a>
                    <a href="register.php" class="btn btn-secondary">Գրանցում</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="container">
        <?php if (isLoggedIn()): ?>
            <div class="add-news-section">
                <a href="add_news.php" class="btn btn-primary">➕ Ավելացնել նորություն</a>
            </div>
        <?php endif; ?>
        
        <h2>Վերջին նորությունները</h2>
        
        <?php if (empty($news)): ?>
            <div class="empty-state">
                <p>Դեռ նորություններ չկան։ Դարձիր առաջինը՝ ավելացնելով նորություն։</p>
            </div>
        <?php else: ?>
            <div class="news-list">
                <?php foreach ($news as $item): ?>
                    <div class="news-card">
                        <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                        <div class="news-content">
                            <?php echo nl2br(htmlspecialchars($item['content'])); ?>
                        </div>
                        <div class="news-meta">
                            <span>Հեղինակ: <?php echo htmlspecialchars($item['author_name']); ?></span>
                            <span>Ամսաթիվ: <?php echo date('d.m.Y H:i', strtotime($item['created_at'])); ?></span>
                            <span>Դիտումներ: <?php echo $item['views']; ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>