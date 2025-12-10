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

// Получаем параметры поиска из GET
$searchType = $_GET['search_type'] ?? 'both'; // По умолчанию поиск и по имени, и по городу
$searchQuery = $_GET['search_query'] ?? '';
$page = $_GET['page'] ?? 1;

// Получаем пользователей с учетом поиска
$users = showUserList($user->id, $searchType, $searchQuery);

// Отладка (убери после проверки)
// p($users);
// echo "Поиск: $searchQuery, Тип: $searchType, Найдено: " . count($users);

require('./templates/head.tpl');
require('./templates/errors.tpl');

// преобразуем данные по фото для пагинации
$paginationData = pagination($users, $page);  

require('./templates/userList.tpl');
require('./templates/pagination_var2.tpl');
require('./templates/footer.tpl');