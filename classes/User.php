<?php

    class User {
        public $name;
        public $email;
        public $password;

        public function __construct($name, $email, $password) {
                $this->$name = $name;
                $this->$email = $email;
                $this->$password = $password;
        }

        public function getUserInfo() {
            echo "<pre>";
            echo " Данные пользователя"; 
            echo " Вес: " . $this->name; 
            echo " Пол : " . $this->email;       
            echo " Возраст : " . $this->password;    
            echo "</pre>";
        }

        // Нужны ли тут методы для регистрации и логина? 
    }