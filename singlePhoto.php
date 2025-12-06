<?php 

ob_start();

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// require('./api/like.php');
require('./helpers/functions.php');
require('./config/database.php');



// Получить данные текущего пользователя
$user = R::load('users', $_SESSION['user_id']);
$userId = $user->id;
 

if (isset($_GET['photo_id']) && is_numeric($_GET['photo_id'])) {
    $photosId = (int)$_GET['photo_id'];
} else {
    // Если не передан или не число, перенаправляем с ошибкой
    $_SESSION['errors'][] = "Ошибка! Не удалось отобразить фото.";
    header('Location: gallery.php');
    exit;
}

// Отображаем фото и информацию по нему
$photo = R::load('photos', $photosId);
// p($photo);

// Берем id от фото и получаем инфу по автору.
$autorId = ($photo['user_id']);
$fileName = $photo['filename'];

// Информация по автору
$autor = R::load('users', $autorId);
$autorName = $autor['name'];

// Путь к фото для передачи в шаблон
$photoPath = "./uploads/photos/" . $autorId  . '/' . $fileName;


$comments = getCommentsWithAuthors($photosId);
$count_comments = count($comments);

 
if(isset($_POST['submit'])) {
    // Проверяем, что комментарий не пустой
    if(!isset($_POST['comment_text']) || trim($_POST['comment_text']) === '') {
        $_SESSION['errors'][] = "Текст комментария не может быть пустым.";
    }

    // Если нет ошибок - добавляем комментарий
    if(empty($_SESSION['errors'])) {
        $comment = addComment($userId, $_POST['comment_text'], $photosId); 

        if($comment) {
            $_SESSION['success'][] = "Комментарий успешно добавлен";
            header('Location: singlePhoto.php?photo_id=' . $photosId);
            exit();
        } else {
            $_SESSION['errors'][] = "Что то пошло не так.";            
        }
    }
}


// смотрим количество лайков на конкретном фото
$totalLikes = R::count('likes', 'photo_id = ?', [$photosId]);
 

require('./templates/head.tpl');
require('./templates/errors.tpl');


require('./templates/singlePhoto.tpl');
require('./templates/footer.tpl');