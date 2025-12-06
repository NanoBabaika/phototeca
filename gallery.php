<?php
    ob_start();

    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }


    require('./helpers/functions.php');
    require('./config/database.php');

    // Получить данные текущего пользователя
    $user = R::load('users', $_SESSION['user_id']);

    // id пользователя
    $id = $user->id;

    require('./templates/head.tpl');
    require('./templates/errors.tpl');
    // userInfo($user);

    // Код отвечающий за загрузку фото
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_FILES["file"]) && $_FILES["file"]["error"] == UPLOAD_ERR_OK) { 
            // Загружаем фото на сервер
            $file_details = loadPhoto($user, $_FILES["file"]);
    
            // передаем данные о файле и пользователе в БД
            if(isset($file_details)) {
                $photoId = addPhotoInBD($file_details);

                if ($photoId) {
                    $_SESSION['success'][] = "Файл загружен и запись в БД успешно создана.";
                } else {
                    $_SESSION['errors'][] = "Файл загружен на сервер, но не удалось создать запись в БД.";
                }
            } else {
                $_SESSION['errors'][] = "Что то пошло не так. Не удалось загрузить файл.";
            }

            header('Location: /gallery.php');
            exit;
        }
    }

    // Получили массив с данными по фотографиям
    $photos = getUserPhotos($user->id);
    
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
 

    // Проверяем существование лайка и отдаем в js после загрузки сервера
    $likedPhotos = likeExamination($id);
    $likedIds = [];
    foreach ($likedPhotos as $inner_array) {
        $likedIds[] = $inner_array['photo_id'];
    }
 
    require('./templates/gallery.tpl');
    require('./templates/pagination.tpl');
    require('./templates/footer.tpl');