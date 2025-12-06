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


    if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
        $other_user_id = (int)$_GET['user_id'];
    } else {
        // Если не передан или не число, перенаправляем с ошибкой
        $_SESSION['errors'][] = "Неверный идентификатор пользователя.";
        header('Location: users.php');
        exit;
    }


    $other_user = R::load('users', $other_user_id);

    $id = $other_user->id;

    $photos = getUserPhotos($id);

    // Если заходим первый раз на страницу устанавливаем как $_GET['page'] = 1;
    if(!isset($_GET['page'])) {
        $_GET['page'] = 1;
    } 
    
    // преобразуем данные по фото для пагинации
    $paginationData = pagination($photos, $_GET['page']);  
     
    $photoIds= [];
    
    foreach($paginationData['items'] as $photo){
        $photoIds[] = $photo['id'];
    }
 

    $stats = showLikesAndCommentsCounts($photoIds);
 

 
    require('./templates/head.tpl');
    require('./templates/errors.tpl');
    ?>

    <?php if(isset($other_user['name'])): ?>
        <h1>📷 Галерея пользователя <?= htmlspecialchars($other_user['name']) ?></h1>
    <?php endif;?>



    <?php
    require('./templates/gallery.tpl');
    require('./templates/footer.tpl');