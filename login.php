<?php
    ob_start();

    require('./helpers/functions.php');
    require('./config/database.php');
    
    
    session_start();

    // Тут должен быть код для логина пользователя 
    
    // 1. Нужно собрать данные из формы. 
    if(isset($_POST['login'])) {

        if ( trim($_POST['email']) == '' ) {
            $_SESSION['errors'][] =  "Для входа на сайт необходимо указать Email...";
        } else if ( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ){
            $_SESSION['errors'][] = "Введите корректный Email....";
        }

        if ( trim($_POST['password']) == '' ) {
            $_SESSION['errors'][] = "Для входа на сайт необходимо ввести пароль....";
        }

        if(empty($errors)) {
             $user = userLogin($_POST['email'], $_POST['password']);
            if($user) {
                $_SESSION['user_id'] = $user->id; 
                header('Location: index.php');
                exit();
            } 
        }  
    }

    require('./templates/errors.tpl');
    require('./templates/login.tpl');