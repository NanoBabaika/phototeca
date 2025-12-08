<?php

    require 'rb-mysql.php';
    
    R::setup('mysql:host=database;dbname=phototeca', 'docker', 'docker');


    define('ROOT', dirname(__FILE__). '/');
    define('HOST', '//' . $_SERVER['HTTP_HOST'] . '/');

