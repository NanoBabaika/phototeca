<?php   
ob_start();


session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
echo "logged user id " . $_SESSION['user_id'];

require('./helpers/functions.php');
require './helpers/resize-and-crop.php';

require('./config/database.php');

// Получить данные текущего пользователя
$user = R::load('users', $_SESSION['user_id']);
$id = $user->id;
echo $id;


if(isset($_POST['edit-profile'])) {
    // p($_POST);


    if(trim($_POST['name']) === '') {
       $_SESSION['errors'][] = "Укажите новое имя пользователя."; 
    }

    if(trim($_POST['email']) === '') {
       $_SESSION['errors'][] = "Поле почта обязательно! Укажите корректный новый адрес. "; 
    }

    // if($_POST['email'] !== $user->email) {
        
    //     // Ищем есть ли уже такой логин в БД
    //     $existingUser = R::findOne('users', ' email = ? AND id != ? ', [trim($_POST['email']), $user->id]);
        
    //     if ($existingUser) {
    //         $_SESSION['errors'][] = "Пользователь с таким email уже зарегистрирован";
    //     }
    // }


    // Если пользователь хочет поменять пароль проверяем вверно ли он ввел старый
    $password_change_requested = false;
    if(isset($_POST['new_password']) && $_POST['new_password'] !== ''){
        $password_change_requested = true;
        
        // Проверяем, введен ли текущий пароль
        if(empty($_POST['current_password'])) {
            $_SESSION['errors'][] = "Для смены пароля введите текущий пароль!"; 
        }
        // Если не совпадает выдаем ошибку данные не меняем
        elseif(!password_verify($_POST['current_password'], $user->password)) {
            $_SESSION['errors'][] = "Не верно указан старый пароль!"; 
        }
    }

 

    if(empty($_SESSION['errors'])) {
        echo "Как будто бы редактируем профиль и грузим фото";
        $id = editProfile($user->id, $_POST, $password_change_requested);
        if($id) {
            $_SESSION['success'][] = "Данные успешно обновлены!";

            // загрузка фото
            if (isset($_FILES["avatar"]) && $_FILES["avatar"]["error"] == UPLOAD_ERR_OK) {
                p($_FILES);
                $result = loadAvatar($user->id, $_FILES["avatar"]);

                if($result === true) {
                    $_SESSION['success'][] = "Аватар в БД успешно обновлен!";
                } else {
                    $_SESSION['errors'][] = "Ошибка при загрузке файла в БД.";
                    return;
                }

            }


            header('Location: profile.php');
            exit;
        } else {
            $_SESSION['errors'][] = "Что-то пошло не так!";
        }
 
    }

    
}


if(isset($_POST['delete-avatar'])) {
    echo "Нажата кнопка уделения автара";

    $result = deleteAvatar($user->id);

    if($result === true) {
        $_SESSION['success'][] = "Аватар успешно удален!";
    } else {
        $_SESSION['errors'][] = "Что то пошло не так.";
        return;
    }

    header('Location: profile.php');
    exit;
}

 

require('./templates/head.tpl');
require('./templates/errors.tpl');
// p($user);
require('./templates/profile-edit.tpl'); 
require('./templates/footer.tpl');