<?php
session_start();

// Удаляем все переменные сессии
$_SESSION = array();

// уничтожаем сессию
session_destroy();

header('Location: login.php');
exit();