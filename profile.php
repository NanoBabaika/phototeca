<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require('./helpers/functions.php');
require('./config/database.php');

// Получить данные текущего пользователя
$user = R::load('users', $_SESSION['user_id']);

require('./templates/head.tpl');
require('./templates/errors.tpl');
require('./templates/profile.tpl'); 
require('./templates/footer.tpl');