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

    //  Получили массив с пользователями 
    $users = showUserList($user->id);
    
    
    require('./templates/head.tpl');
    require('./templates/errors.tpl');

    // Если заходим первый раз на страницу устанавливаем как $_GET['page'] = 1;
    if(!isset($_GET['page'])) {
        $_GET['page'] = 1;
    } 
 
    // преобразуем данные по фото для пагинации
    $paginationData = pagination($users, $_GET['page']);  

 

    require('./templates/userList.tpl');
    require('./templates/pagination.tpl');
    require('./templates/footer.tpl');
