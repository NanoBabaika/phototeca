<?php
require('../helpers/functions.php');
require('../config/database.php');

// Проверяем сессию/авторизацию
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "Ошибка: Необходима авторизация";
    exit;
}

if (isset($_POST['user_id'])) {
    $userIdToDelete = (int)$_POST['user_id'];
    
    // Проверяем, что пользователь удаляет именно свой профиль
    // if ($userIdToDelete !== $_SESSION['user_id']) {
    //     echo "Ошибка: Вы можете удалить только свой профиль";
    //     exit;
    // }

    $result = deleteUser($userIdToDelete);
    echo $result;
} else {
    echo "Ошибка: ID пользователя не указан.";
}

function deleteUser($userId) {
    $user = R::load('users', $userId);  
    
    if ($user->id !== 0) {
        // 1. Удаляем папку с фотографиями пользователя
        $photosDir = "../uploads/photos/" . $userId;
        if (file_exists($photosDir)) {
            deleteDirectory($photosDir);
        }
        
        // 2. Удаляем аватар пользователя (если он не дефолтный)
        if (!empty($user->avatar) && $user->avatar !== '01.jpeg') {
            $avatarPath = "../uploads/avatars/" . $user->avatar;
            if (file_exists($avatarPath)) {
                unlink($avatarPath);
            }
        }
        
        // 3. Удаляем пользователя из базы
        R::trash($user);
        
        // 4. Завершаем сессию
        session_destroy();
        
        return "success";
    } else {
        return "Ошибка: Пользователь не найден";
    }
}

// Функция для рекурсивного удаления директории
function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }
    
    if (!is_dir($dir)) {
        return unlink($dir);
    }
    
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        
        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }
    
    return rmdir($dir);
}
 