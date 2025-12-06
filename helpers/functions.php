<?php


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

    function userInfo($user) {
        if(isset($user)) {
            echo "<pre>";
            echo "Пользователь залогинен";
            echo "<pre/>";
            echo "<pre>";
            echo "id пользователя: " . $user->id;
            echo "</pre>";
            echo "<pre>";
            echo  "Почта пользователя: " . $user->email;
            echo "<pre>";
        } else {
            echo "Пользователь не залогинен.";
        }
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
        // Конечная папка для загрузки
        $upload_dir = "./uploads/photos/" . $userId . "/"; 
        
        // строка с временным хранением файла
        $temp_file = $_FILES["file"]["tmp_name"];

        $mime_type = mime_content_type($temp_file);

        if ($mime_type !== 'image/jpeg' && $mime_type !== 'image/png' && $mime_type !== 'image/jpg') {
            $_SESSION['errors'][] = "Недопустимый тип файла. Допускаются только JPEG и PNG.";
            return;
        }

        // Лимит в 5 МБ ОГРАНИЧЕНИЕ!
        $max_file_size = 5242880; 
        $current_file_size = $_FILES["file"]["size"];
        
        if($current_file_size > $max_file_size) {
            $_SESSION['errors'][] = "Ошибка: Размер файла превышает допустимый лимит (5 МБ).";
            return; 
        }
 

        // Получаем расширение оригинального файла
        $original_file_name = basename($_FILES["file"]["name"]);
        $file_info = pathinfo($original_file_name);
        $extension = $file_info['extension'];

        // Генерируем уникальное имя файла
        // Используем uniqid() для генерации уникальной строки
        $new_file_name = uniqid() . '.' . $extension;

        // Обновляем целевой путь загрузки на новое имя
        // Теперь $target_file содержит безопасный путь и имя
        $target_file = $upload_dir . $new_file_name;

        // Создаем директорию, если она не существует
        if (!is_dir($upload_dir)) {
            // Создаем директорию рекурсивно (true в третьем аргументе), 
            // и устанавливаем права доступа 0777 (восьмеричное представление)
            if (mkdir($upload_dir, 0777, true)) {
                $_SESSION['success'][] = "Директория для пользователя $userId успешно создана с правами 777.";
            } else {
                $_SESSION['errors'][] = "Ошибка: Не удалось создать директорию для пользователя $userId.";
                return;
            }
        }

        
        // Перемещаем загруженный файл в целевую директорию
        if (move_uploaded_file($temp_file, $target_file)) {
            $_SESSION['success'][] = "Файл успешно загружен.";

            // Возвращаем массив что бы передать в addPhotoInBD
        return [
            'user_id' => $userId,
            'file_name' => $new_file_name, // Сгенерированное уникальное имя
            'original_name' => $original_file_name, // <<< Вот оно
            'file_path' => $target_file,
            'mime_type' => $mime_type,
            'file_size' => $current_file_size
        ];

        } else {
            $_SESSION['errors'][] = "Ошибка при загрузке файла.";
            return false;
        }
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
        echo "Нажата кнопка редактировать профиль";

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
            echo "Пароль обновлен!";
        }
        $id = R::store($user);

        echo "Данные пользователя успешно обновлены,  ID: " . $id;

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


        // Если перемещение файла прошло успешно выведем сообщение нет вернем ошибку
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


    function showUserList($id) {

        $users = R::getAll("
            SELECT 
                u.id,
                u.name,
                u.city,
                u.avatar,
                COUNT(p.id) as photos_count
            FROM users u
            LEFT JOIN photos p ON p.user_id = u.id
            WHERE u.id != ?
            GROUP BY u.id
            ORDER BY photos_count DESC
        ", [$id]); 
        

        // Эти данные мне нужно вернуть в виде массива пользователи. 
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
            echo "Лайк успешно добавлен!";
        } else {
            // тут должен быть код для удаления записи
            $like = R::findOne('likes', 'photo_id = ? AND user_id = ?', [$photosId, $userId]);
            R::trash($like);
            echo "Лайк успешно удален!";
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
            echo  "Пользователь уже поставил лайк этой фотографии.";
            return true;
        } else {
            echo  "Лайка пока нет.";
            return false;
        }
    }
 
            
    function showLikesAndCommentsCounts($photoIds) {
        // Подготавливаем плейсхолдеры для IN()
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
        
        // мы должны получить id фото которые лайкнул пользователь 
        $likes = R::getAll(
            'SELECT photo_id FROM likes WHERE user_id = ?', 
            [$userId]
        );

        return $likes; 
    }


    function sendEmailForResetPass($userEmail) {
        // Запрос  проверяем есть ли почта в БД
        $user = R::findOne('users', 'email = ?', [$userEmail]);
        
        if ($user) {
            $_SESSION['success'][] = "Письмо с восстановлением пароля отправлено на эл. почту пользователя"; 
        } else {
            $_SESSION['errors'][] = "Ошибка! Пользователя с такой почтой не существует.";
            return;
        }

        return $user; 
    }



































 