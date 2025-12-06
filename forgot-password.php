<?php
    session_start();

    require('./helpers/functions.php');
    require('./config/database.php');
  
    if(isset($_POST['submit'])) {
        echo "нажата кнопка отправить ссылку";
        p($_POST);
    }


    require('./templates/errors.tpl');
    require('./templates/forgot_password.tpl');
