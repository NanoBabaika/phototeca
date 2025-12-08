<?php
    session_start();


    require('./helpers/functions.php');
    require('./config/database.php');
 
     
  
    if(isset($_POST['submit'])) {
        $userEmail = trim($_POST['email']);
        // проверяем существует ли пользователь
        $user = R::findOne('users', 'email = ?', [$userEmail]);

        if($user) {
            // если существует отправляем ссылку для восстановления
            $result = sendPasswordRecoveryEmail($userEmail);
            if($result) {
                $_SESSION['success'][] = "Письмо отправлено проверьте Вашу почту";    
            } else {
                $_SESSION['errors'][] = "При отправке письма что то пошло не так.";    
            }

        } else {
            // отображаем ошибку
            $_SESSION['errors'][] = "Пользователь с таким  Email не зарегестирован"; 
        }

    }


    require('./templates/errors.tpl');
    require('./templates/forgot_password.tpl');
