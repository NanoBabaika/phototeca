<?php
    require_once __DIR__ . '/../lib/PHPMailer/src/PHPMailer.php';
    require_once __DIR__ . '/../lib/PHPMailer/src/SMTP.php';
    require_once __DIR__ . '/../lib/PHPMailer/src/Exception.php';
    require_once __DIR__ . '/../config/constants.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;


    function p($var) {
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }


    function pd($var) {
        echo "<pre>";
        print_r($var);
        echo "</pre>";
        die();
    }
 
    // напишем функцию для регистрации пользователя
    function register($name, $email, $password) {
        // Проверяем есть ли пользователь с такой почтой
        $existingUser = R::findOne('users', ' email = ? ', [trim($email)]);
        if ($existingUser) {
            $_SESSION['errors'][] = "Пользователь с таким email уже зарегистрирован";
            return false;
        }

        // 1. Создание нового "боба" (объекта-записи) типа 'user' (соответствует таблице 'users')
        $user = R::dispense('users');

        // Хешируем пароль
        $password = password_hash($password, PASSWORD_DEFAULT);


        // 2. Установка свойств (столбцов) для этого "боба"
        $user->name  = $name;
        $user->email  = $email;
        $user->password = $password;
       
        // 3. Сохранение "боба" в базе данных
        // R::store() вернет ID новой записи
        $id = R::store($user);

        echo "Новая запись успешно создана с ID: " . $id;

        return $user; 
    }

    
    
    function userLogin($email, $password) {
        // 1. Поиск пользователя в БД по email
        // Используем findOne для получения одного бина
        $user = R::findOne('users', ' email = ? ', [$email]);
        
        // 2. Проверка, найден ли пользователь
        if (!$user) {
            $_SESSION['errors'][] = "Пользователь с таким email не зарегистрирован";
            return null;
        }
    
        // 3. Проверка пароля с использованием стандартной функции PHP
        // password_verify сравнивает введенный открытый пароль 
        // с хешем, извлеченным из базы данных.
        if (password_verify($password, $user->password)) {
            // Пароль совпал, логин успешен
            return $user;
        } else {
            // Пароль не совпал
            $_SESSION['errors'][] = "Пользователь с такими данными не зарегистрирован...";
            return null;
        }
    }


    function loadPhoto($user, $file) {
        $userId = $user->id;
        $upload_dir = "./uploads/photos/" . $userId . "/"; 
        $temp_file = $_FILES["file"]["tmp_name"];
        
        // Проверка MIME-типа
        $mime_type = mime_content_type($temp_file);
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        
        if (!in_array($mime_type, $allowed_types)) {
            $_SESSION['errors'][] = "Недопустимый тип файла. Допускаются: JPEG, PNG, GIF, WebP.";
            return false;
        }
        
        // Лимит размера (5 МБ)
        $max_file_size = 5242880; 
        $current_file_size = $_FILES["file"]["size"];
        
        if($current_file_size > $max_file_size) {
            $_SESSION['errors'][] = "Ошибка: Размер файла превышает допустимый лимит (5 МБ).";
            return false; 
        }
        
        // Получаем данные об оригинальном файле
        $original_file_name = basename($_FILES["file"]["name"]);
        $file_info = pathinfo($original_file_name);
        $extension = strtolower($file_info['extension']);
        
        // Генерируем уникальное имя
        $new_file_name = uniqid() . '.' . $extension;
        
        // Создаем директории
        $thumbs_dir = $upload_dir . "thumbs/";
        
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                $_SESSION['errors'][] = "Ошибка: Не удалось создать директорию для пользователя $userId.";
                return false;
            }
        }
        
        if (!is_dir($thumbs_dir)) {
            if (!mkdir($thumbs_dir, 0777, true)) {
                $_SESSION['errors'][] = "Ошибка: Не удалось создать директорию для миниатюр.";
                return false;
            }
        }
        
        // Пути для сохранения
        $original_path = $upload_dir . $new_file_name;
        $thumb_path = $thumbs_dir . $new_file_name;
        
        // Сохраняем оригинал
        if (!move_uploaded_file($temp_file, $original_path)) {
            $_SESSION['errors'][] = "Ошибка при загрузке файла.";
            return false;
        }
        
        // Создаем миниатюру (300x300)
        require_once 'resize-and-crop.php'; // Подключаем твою функцию
        
        $thumb_created = resize_and_crop($original_path, $thumb_path, 300, 300);
        
        if (!$thumb_created) {
            $_SESSION['errors'][] = "Ошибка при создании миниатюры.";
            // Оригинал всё равно сохраняем
        } else {
            $_SESSION['success'][] = "Фото и миниатюра успешно загружены.";
        }
        
        // Возвращаем данные
        return [
            'user_id' => $userId,
            'file_name' => $new_file_name,
            'original_name' => $original_file_name,
            'file_path' => $original_path,
            'thumb_path' => $thumb_path,
            'mime_type' => $mime_type,
            'file_size' => $current_file_size
        ];
    }

    
    function addPhotoInBD($file_details) {

        try {
        // Создаем новый bean типа 'photos'
        $photo = R::dispense('photos'); // Имя таблицы будет 'photos'

        // Заполняем bean данными из переданного массива
        $photo->user_id        = $file_details['user_id'];
        $photo->filename       = $file_details['file_name'];      // Уникальное имя на сервере
        $photo->original_name  = $file_details['original_name']; // <<< Оригинальное имя от пользователя
        $photo->description    = '';                           // Поле необязательное, пока пустое
        $photo->created_at     = R::isoDateTime();             // Метка времени


        // Сохраняем bean и получаем ID
        $id = R::store($photo);
   
        return $id;

         } catch (Exception $e) {
             // Обработка ошибок БД
             Log::error("Ошибка сохранения фото в БД: " . $e->getMessage());
             $_SESSION['errors'][] = "Фото не загружено";
             return false;
         }

    }

    function getUserPhotos($user_id) {

        // SQL-запрос: SELECT * FROM photos WHERE user_id = ?
        if($user_id) {
            $photos = R::findAll('photos', 'user_id = ? ORDER BY created_at DESC', [$user_id]);
        } else {
            $_SESSION['errors'][] = "Нет пользователя с таким идентификатором.";
            return;
        }
       
        // 3. Выполнить запрос через RedBeanPHP
        // 4. Если фото найдены: вернуть массив объектов/массивов
        // 5. Если фото нет: вернуть пустой массив
        // 6. Обработать возможные ошибки БД

        return $photos;
    }


    // функции которые относятся к профилю

    function editProfile($id, $data, $password_change_requested) {
 
        $user = R::findOne('users', ' id = ? ', [$id]);
        
        // Обязательные поля!
        $user->name = ($data['name']);
        $user->email = ($data['email']);

        // Если поле пустное не обновляем данные!
        if( isset($data['city']) && $data['city'] !== '') {
        $user->city = ($data['city']);
        }

        // Если поле пустное не обновляем данные!
        if( isset($data['phone']) && $data['phone'] !== '') {
        $user->phone = ($data['phone']);
        }
        
        // Если поле пустное не обновляем данные!
        if($password_change_requested && isset($data['new_password']) && $data['new_password'] !== '') {
            $password = password_hash(trim($data['new_password']), PASSWORD_DEFAULT);
            $user->password = $password;
        }
        $id = R::store($user);

 

        return $id; 
    }

    // Загрузка аватара
    function loadAvatar($id, $file) {
  
        // Директория для загрузки
        $upload_dir = "./uploads/avatars/";

        // Получаем старый аватар ДО загрузки нового
        $user = R::findOne('users', ' id = ? ', [$id]);
        $oldAvatar = $user->avatar;

        // Записываем параметры файла в переменные
        // Временное имя файла из хранилища
        $originalName = $_FILES["avatar"]["name"];  
        $temp_file = $_FILES["avatar"]["tmp_name"];
        $fileSize = $_FILES["avatar"]["size"];
        $fileType = $_FILES["avatar"]["type"];

        // Получаем расширение файла отделив символы после запятой
        // получили массив с названием и расширением.
        $arrFileInfo = explode(".", $originalName);
        // Получили последний элемент массива и записали его в переменную
        $extenstion =  end($arrFileInfo);
    
        // Получаем уникальное имя файла c расширением
        $newFileName = uniqid() . '.' . $extenstion;

        // полный путь к файлу с именем
        $fileAndPath =  $upload_dir . $newFileName;

        // ___________Здесь будут проверки файла___________________________
        
        // Лимит в 5 МБ ОГРАНИЧЕНИЕ!
        // Максимальный размер файла в байтах
        $max_file_size = 5242880;  
        
        if($fileSize > $max_file_size) {
            $_SESSION['errors'][] = "Ошибка: Размер файла превышает допустимый лимит (5 МБ).";
            return; 
        }

        // Тип файла
        // ТУТ ОШИБКА!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        $mime_type = mime_content_type($temp_file);

        if ($mime_type !== 'image/jpeg' && $mime_type !== 'image/png' && $mime_type !== 'image/jpg') {
            $_SESSION['errors'][] = "Недопустимый тип файла. Допускаются только JPEG и PNG.";
            return;
        }

        // Возможно будут еще проверки.


        // Если перемещение файла прошло успешно выведем сообщение, нет вернем ошибку
        if (move_uploaded_file($temp_file, $fileAndPath)) {
            $_SESSION['success'][] = "Новый аватар успешно загружен(файл).";
        } else {
            $_SESSION['errors'][] = "Ошибка при загрузке файла.";
            return false;
        }


        // ОБРЕЗКА АВАТАРА
        $avatar_size = 200; // например, 200x200 пикселей
        $resized_avatar_path = $upload_dir . 'resized_' . $newFileName;
        
        if (resize_and_crop($fileAndPath, $resized_avatar_path, $avatar_size, $avatar_size)) {
            // Удаляем оригинал большой файл, оставляем только оптимизированный
            unlink($fileAndPath);
            $newFileName = 'resized_' . $newFileName;
            $_SESSION['success'][] = "Аватар успешно загружен и оптимизирован!";
        } else {
            $_SESSION['errors'][] = "Ошибка при обработке изображения.";
            return false;
        }

        // Обновляем аватар в БД
        $user->avatar = $newFileName;
        R::store($user);
        
        // ✅ УДАЛЯЕМ СТАРЫЙ АВАТАР
        if ($oldAvatar && file_exists($upload_dir . $oldAvatar)) {
            unlink($upload_dir . $oldAvatar);
        }
        
        return true;
        
    }

    // Удаление автара
    function deleteAvatar($userId) {
        $upload_dir = "./uploads/avatars/";
        
        $user = R::findOne('users', ' id = ? ', [$userId]);
        if ($user && $user->avatar) {
            $avatarPath = $upload_dir . $user->avatar;

            // Удаляем файл
            if (file_exists($avatarPath)) {
                unlink($avatarPath);
            }

            // Очищаем поле в БД
            $user->avatar = null;
            R::store($user);

            return true;
        } else {
            return false;
        }
    
    }


    function showUserList($excludeId, $searchType = null, $searchTerm = null) {
                
        $sql = "
            SELECT 
                u.id,
                u.name,
                u.city,
                u.avatar,
                COUNT(p.id) as photos_count
            FROM users u
            LEFT JOIN photos p ON p.user_id = u.id
            WHERE u.id != ?
        ";
        
        $params = [$excludeId];
        
        // Добавляем условия поиска
        if ($searchTerm && $searchType) {
            if ($searchType === 'name') {
                $sql .= " AND u.name LIKE ? ";
                $params[] = '%' . $searchTerm . '%';
            } elseif ($searchType === 'city') {
                $sql .= " AND u.city LIKE ? ";
                $params[] = '%' . $searchTerm . '%';
            } elseif ($searchType === 'both') {
                // Поиск по имени ИЛИ городу
                $sql .= " AND (u.name LIKE ? OR u.city LIKE ?) ";
                $params[] = '%' . $searchTerm . '%';
                $params[] = '%' . $searchTerm . '%';
            }
        }
        
        $sql .= " GROUP BY u.id ORDER BY photos_count DESC";
        
        $users = R::getAll($sql, $params);
        
        return $users;
    }

    function pagination($data, $current_page = 1) {
        $per_page = 4;

        // Текущая страница
        $current_page = $_GET['page'] ?? 1;
        
        // Общее количество элементов (из массива)
        $total_items = count($data);
        
        // Вычисляем смещение
        $offset = ($current_page - 1) * $per_page;
 
        
        // Берем только нужную часть массива
        $items = array_slice($data, $offset, $per_page);
        
        // Общее количество страниц
        $total_pages = ceil($total_items / $per_page);

        // Если запросили страницу котьорой не существует, то показываем последнюю доступнуюю
        if ($current_page > $total_pages) {
            $current_page = $total_pages;
        }
        
        $result = [
            'total_pages' => $total_pages,
            'current_page' => $current_page,
            'items' => $items,
            'total_items' => $total_items
        ];
        
        return $result;
    }


    // функции для работы с комментариями

    function addComment($userId, $textComment, $photosId) {
        // проверка текста от HTML-тегов перед записью в БД
        $cleanTextComment = htmlspecialchars(strip_tags($textComment), ENT_QUOTES, 'UTF-8');

        // Создали новый "боб"
        $comment = R::dispense('comments');
    
        // Наполняем его
        $comment->photo_id = $photosId;
        $comment->user_id = $userId;
        $comment->comment_text = $cleanTextComment;
        
        // Задаем время комментария или его обновления.
        $currentTime = date('Y-m-d H:i:s');
        $comment->created_at = $currentTime;
        $comment->updated_at = $currentTime; 
       
        // 3. Сохранение "боба" в базе данных
        // R::store() вернет ID новой записи
        $comment = R::store($comment);
 
        return $comment;    
    }
 

    function getCommentsWithAuthors($photoId) {
        return R::getAll("
            SELECT 
                c.*,
                u.name as user_name,
                u.avatar as user_avatar
            FROM comments c
            LEFT JOIN users u ON c.user_id = u.id
            WHERE c.photo_id = ?
            ORDER BY c.created_at DESC
        ", [$photoId]);
    }


   function handleLike($photoId, $userId) {
        $likeStatus = showLikeSinglePhoto();
    
        if($likeStatus === false) {
            $like = R::dispense('likes');

            $like->photo_id = $photosId;
            $like->user_id = $userId;
            $currentTime = date('Y-m-d H:i:s');
            $like->created_at = $currentTime;
            
            // 3. Сохранение "боба" в базе данных
            // R::store() вернет ID новой записи
            $like = R::store($like);
 
        } else {
            // тут должен быть код для удаления записи
            $like = R::findOne('likes', 'photo_id = ? AND user_id = ?', [$photosId, $userId]);
            R::trash($like);

        }
}

    function totalLikes($photoId) {
        $totalLikes = R::count('likes', 'photo_id = ?', [$photoId]);
        return $totalLikes;
    }

    function showLikeSinglePhoto($userId, $photoId) {
        //  запрос в БД мы должны найти по ИД фото и узнать ставил ли пользователь лайк
        $likesCount = R::count('likes', 'photo_id = ? AND user_id = ?', [$userId, $photoId]);

        if($likesCount > 0) {
 
            return true;
        } else {

            return false;
        }
    }
 
            
    function showLikesAndCommentsCounts($photoIds) {
        // Подготавливаем плейсхолдеры для IN()
            // Если массив пустой, возвращаем пустые данные
        if (empty($photoIds)) {
            return [
                'likes' => [],
                'comments' => []
            ];
        }

        $placeholders = implode(',', array_fill(0, count($photoIds), '?'));

        // Получаем количество комментариев для каждой фотографии
        $commentsCount = R::getAll("
            SELECT photo_id, COUNT(*) as count 
            FROM comments 
            WHERE photo_id IN ($placeholders) 
            GROUP BY photo_id
        ", $photoIds);

        // Получаем количество лайков для каждой фотографии
        $likesCount = R::getAll("
            SELECT photo_id, COUNT(*) as count 
            FROM likes 
            WHERE photo_id IN ($placeholders) 
            GROUP BY photo_id
        ", $photoIds);

        // Преобразуем в удобный формат (photo_id => count)
        $commentsMap = [];
        foreach ($commentsCount as $item) {
            $commentsMap[$item['photo_id']] = $item['count'];
        }

        $likesMap = [];
        foreach ($likesCount as $item) {
            $likesMap[$item['photo_id']] = $item['count'];
        }


        $galleryStats = [
            'likes' => $likesMap,
            'comments' => $commentsMap 
        ];
         
        return $galleryStats;
    }

    // функция проверки лайкал ли пользователь фото
    function likeExamination ($userId) {
        // Проверяем, есть ли пользователь
        if (!$userId) {
            return [];
        }
        
        // мы должны получить id фото которые лайкнул пользователь 
        $likes = R::getAll(
            'SELECT photo_id FROM likes WHERE user_id = ?', 
            [$userId]
        );

        return $likes; 
    }

    // отправка письма
    function sendEmail($to, $subject, $message) {
        try { 
        
            // Или если установлен вручную
            require_once __DIR__ . '/../lib/PHPMailer/src/PHPMailer.php';
            require_once __DIR__ . '/../lib/PHPMailer/src/SMTP.php';
            require_once __DIR__ . '/../lib/PHPMailer/src/Exception.php';
            
            // Создаем экземпляр
            $mail = new PHPMailer(true);
            
            // Настройки сервера
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = SMTP_AUTH;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_SECURE;
            $mail->Port = SMTP_PORT;
 
            
            // Кодировка
            $mail->CharSet = 'UTF-8';
            
            // От кого
            $mail->setFrom(SMTP_USERNAME, SITE_NAME);
            $mail->addReplyTo(SMTP_USERNAME, SITE_NAME);
            
            // Кому
            $mail->addAddress($to);
            
            // Тема письма
            $mail->Subject = $subject;
            
            // HTML тело письма
            $mail->isHTML(true);
            $mail->Body = $message;
            
            // Альтернативное текстовое тело
            $mail->AltBody = strip_tags($message);
            
            // Отправляем
            if ($mail->send()) {
                return true;
            } else {
                error_log('Ошибка отправки письма: ' . $mail->ErrorInfo);
                return false;
            }
            
        } catch (Exception $e) {
            error_log('PHPMailer Exception: ' . $e->getMessage());
            return false;
        }
    }
    
    // формирование письма и ссылки
    function sendPasswordRecoveryEmail($email) {
         $user = R::findOne('users', 'email = ?', [$email]);
        
        if (!$user) { 
           return false;
        }

        $user_id = $user->id;
                 
        // Генерируем токен
        $token = bin2hex(random_bytes(50));
        $created_at = date('Y-m-d H:i:s');
        $expires_at = date('Y-m-d H:i:s', time() + 3600); // 1 час
        
        // Удаляем старые токены для этого пользователя
        R::exec("DELETE FROM password_reset WHERE user_id = ?", [$user->id]);
        
   
        
        $sql = "INSERT INTO password_reset (user_id, token, created_at, expires_at) 
            VALUES (?, ?, ?, ?)";
        
        try {
            R::exec($sql, [$user_id, $token, $created_at, $expires_at]);
        } catch (Exception $e) {
            error_log("Ошибка сохранения токена (SQL): " . $e->getMessage());
            return false;
        }

        // Динамически определяем домен
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        
        // Ссылка для восстановления
        $resetLink = $protocol . $host . "/reset-password.php?token=" . $token;

 
        
        // HTML шаблон письма
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; }
                .button { display: inline-block; padding: 12px 24px; background: #667eea; color: #fff; 
                        text-decoration: none; border-radius: 5px; font-weight: bold; }
                .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; 
                        font-size: 12px; color: #777; }
            </style>
        </head>
        <body>
            <div class="container">
                <h2>Восстановление пароля</h2>
                <p>Здравствуйте!</p>
                <p>Вы запросили восстановление пароля для вашего аккаунта в <strong>ФОТОТЕКА</strong>.</p>
                <p>Для установки нового пароля нажмите кнопку ниже:</p>
                
                <p style="text-align: center; margin: 30px 0; color: #c9cf1bff;">
                    <a href="' . $resetLink . '" class="button">Восстановить пароль</a>
                </p>
                
                <p>Или скопируйте ссылку в браузер:</p>
                <p style="word-break: break-all; background: #f5f5f5; padding: 10px; border-radius: 5px; font-size: 12px;">
                    ' . $resetLink . '
                </p>
                
                <p><strong>Ссылка действительна 1 час.</strong></p>
                <p>Если вы не запрашивали восстановление, проигнорируйте это письмо.</p>
                
                <div class="footer">
                    <p>Это письмо отправлено автоматически. Пожалуйста, не отвечайте на него.</p>
                    <p>© ' . date('Y') . ' ФОТОТЕКА. Все права защищены.</p>
                </div>
            </div>
        </body>
        </html>';
        
        // Отправляем через PHPMailer
        return sendEmail($email, 'Восстановление пароля в ФОТОТЕКА', $html);
    }


    // Удаление фото из галлереи
    function deletePhotoFromGallery($photos_id, $autorId) {
            try {
            // нашли фото по id
            $photo = R::findOne('photos', 'id = ?', [$photos_id]);
            
            if (!$photo) {
                return ['status' => 'error', 'message' => 'Фотография не найдена'];
            }
            
            $fileName = $photo['filename'];

            // нашли путь к файлу!!! 
            $filePath = "./uploads/photos/". $autorId  . '/' . $fileName;
            p($filePath);
            // // удаляем из БД 
            R::trash($photo);
            
            // Удаляем сам файл с сервера
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            // сигнализиуем о успехе и перенаправляем пользователя
            $_SESSION['success'] = 'Фотография успешно удалена';
            header('Location: gallery.php');

        } catch (Exception $e) {
            error_log("Ошибка при удалении фото: " . $e->getMessage());
            $_SESSION['errors'][] = 'Произошла ошибка при удалении';
        }

    }

    // показ статистики пользователя
    function userStats($userId) {
        $stats = [];

        // возвращаем счетчик фото пользователя
        $totalPhotosCount = R::count('photos', 'user_id = ?', [$userId]);
        $stats[] = $totalPhotosCount;

        // проверяем была ли загрузка сегодня
        $todayPhotosCount = R::count('photos', 'user_id = ? AND DATE(created_at) = CURDATE()', [$userId]);

        // Дата последней загрузки
        $lastPhotoData = R::getCell('SELECT MAX(created_at) FROM photos WHERE user_id = ?', [$userId]);
        if ($lastPhotoData) {
            $lastUploadDate = date('d.m.Y', strtotime($lastPhotoData));
        } else {
            $lastUploadDate = 'Нет фото';
            $lastUploadTime = '';
        }

        $stats[] = $lastUploadDate;    
        $stats[] = $todayPhotosCount;

        return $stats;

    }

    // статистика по файлам пользователя
/**
 * Получить общий размер фотографий пользователя
 * @param int $userId - ID пользователя
 * @return array - Массив с размерами в разных форматах
 */
function getUserPhotosSize($userId) {
    // Путь к папке пользователя
    $userDir = "./uploads/photos/" . $userId . "/";
    
    // Проверяем, существует ли папка
    if (!is_dir($userDir)) {
        return [
            'bytes' => 0,
            'readable' => '0 Б',
            'files_count' => 0,
            'folder_exists' => false
        ];
    }
    
    // Открываем папку
    $files = scandir($userDir);
    $totalSize = 0;
    $fileCount = 0;
    
    // Проходим по всем файлам в папке
    foreach ($files as $file) {
        // Пропускаем специальные записи . и ..
        if ($file == '.' || $file == '..') {
            continue;
        }
        
        $filePath = $userDir . $file;
        
        // Проверяем, что это файл (а не папка)
        if (is_file($filePath)) {
            // Получаем размер файла в байтах
            $fileSize = filesize($filePath);
            $totalSize += $fileSize;
            $fileCount++;
        }
    }
    
    // Функция для форматирования размера
    function formatSize($bytes) {
        $units = ['Б', 'КБ', 'МБ', 'ГБ', 'ТБ'];
        $i = 0;
        
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        
        // Округляем до 2 знаков после запятой
        return round($bytes, 2) . ' ' . $units[$i];
    }
    
    return [
        'bytes' => $totalSize,
        'readable' => formatSize($totalSize),
        'files_count' => $fileCount,
        'folder_exists' => true
    ];
}



























 