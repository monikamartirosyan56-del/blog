<?php
// logout.php - Ելք
require_once 'config.php';

// Պատռել բոլոր սեսիոն տվյալները
session_destroy();

// Ուղղորդել գլխավոր էջ
header('Location: index.php');
exit();
?>