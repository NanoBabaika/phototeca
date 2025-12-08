<?php
ob_start();

session_start();
require('./helpers/functions.php');
require('./config/database.php');
require('./templates/errors.tpl');

 // Для отладки
error_log("Токен: " . ($_GET['token'] ?? 'отсутствует'));
error_log("POST данные: " . print_r($_POST, true));

// Проверка подключения к БД
try {
    $test_user = R::findOne('users', '1=1 LIMIT 1');
    error_log("Подключение к БД: OK, пользователей найдено: " . ($test_user ? 1 : 0));
} catch (Exception $e) {
    error_log("Ошибка БД: " . $e->getMessage());
}

// 1. Если есть токен в GET-параметрах
if (isset($_GET['token'])) {
    $token = trim($_GET['token']);
    
    // 2. Найти токен в таблице password_reset
    $reset_request = R::findOne('password_reset', 'token = ?', [$token]);
    
    if (!$reset_request) {
         $_SESSION['errors'][] = "Неверная или устаревшая ссылка восстановления.";
    } else {
        // 3. Проверить, не истёк ли токен
        $now = date('Y-m-d H:i:s');
        
        if ($reset_request->expires_at <= $now) {
             $_SESSION['errors'][] = "Ссылка для восстановления истекла. Запросите новую.";
        } else {
            // 4. Если форма отправлена (POST) - обновить пароль
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
                $new_password = $_POST['new_password'] ?? '';
                $confirm_password = $_POST['confirm_password'] ?? '';
                
                // Валидация
                if (strlen($new_password) < 6) {
                     $_SESSION['errors'][] = "Пароль должен быть не менее 6 символов";
                } elseif ($new_password !== $confirm_password) {
                     $_SESSION['errors'][] = "Пароли не совпадают";
                } elseif (empty($errors)) {
                    // Хэшируем новый пароль
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    
                    // Находим пользователя
                    $user = R::findOne('users', 'id = ?', [$reset_request->user_id]);
                    
                    if ($user) {
                        // Обновляем пароль пользователя
                        $user->password = $hashed_password;
                        R::store($user);
                        
                        // Удаляем использованный токен
                        R::trash($reset_request);
                                                
                        // Редирект на логин
                        $_SESSION['success'][] = "Пароль успешно изменён! Теперь войдите с новым паролем.";
                        header('Location: login.php');
                        exit;
                    } else {
                         $_SESSION['errors'][] = "Пользователь не найден";
                    }
                }
            }
        }
    }
} else {
     $_SESSION['errors'][] = "Отсутствует токен восстановления.";
}

// Сохраняем ошибки в сессии для отображения в шаблоне
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
}

require('./templates/errors.tpl');
require('./templates/reset_password.tpl');