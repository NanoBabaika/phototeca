<?php
    ob_start();

    require('./helpers/functions.php');
    require('./config/database.php');

    session_start();

    // $errors = [];
    // $success = [];

    if(isset($_POST['register'])) {
        // проверяем заполненность полей, и если массив с ошибками пуст добавляем запись
        if(trim($_POST['name']) == '' ) { 
             $_SESSION['errors'][] = "Укажите Ваше имя или Никнейм что бы пользователи могли к Вам обращаться.";     
        }

        if ( trim($_POST['email']) == '' ) {
            $_SESSION['errors'][] =  "Email обязателен для регистрации на сайте";
        } else if ( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ){
            $_SESSION['errors'][] = "Введите корректный Email";
        }

        if ( trim($_POST['password']) == '' ) {
            $_SESSION['errors'][] = "Введите пароль";
        }

        if (strlen($_POST['password']) < 5) {
            $_SESSION['errors'][] = "Длинна пароля не может быть меньше 5ти символов";
        }
 

        if(empty($_SESSION['errors'])) {
            $_SESSION['success'][] = "Пользователь успешно зарегистирован"; 
            $user = register($_POST['name'], $_POST['email'], $_POST['password']); 
            if($user) {
               $_SESSION['success'][] = "Пользователь успешно зарегистирован";
            }
            // Сохраняем пользователя в сессию
            $_SESSION['user_id'] = $user->id; 
        }

        header('Location: index.php');
        exit();
    }

    require('./templates/errors.tpl');
    require('./templates/register.tpl');