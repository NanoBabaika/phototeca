<?php
// Очищаем буферы
while (ob_get_level()) {
    ob_end_clean();
}

// Устанавливаем заголовок JSON
header('Content-Type: application/json');

// Подключаем зависимости
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/functions.php';

// Запускаем сессию, если еще не запущена
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Проверяем авторизацию
if (empty($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authorized']);
    exit;
}

$userId = $_SESSION['user_id'];

// Получаем JSON данные
$json_input = file_get_contents('php://input');
$data = json_decode($json_input, true);

// Проверяем корректность данных
if (!isset($data['photo_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

$photoId = (int)$data['photo_id'];

try {
    // Проверяем, лайкал ли уже пользователь
    $existingLike = R::findOne('likes', 'photo_id = ? AND user_id = ?', [$photoId, $userId]);
    
    if ($existingLike) {
        R::trash($existingLike);
        $action = 'unliked';
    } else {
        $like = R::dispense('likes');
        $like->photo_id = $photoId;
        $like->user_id = $userId;
        $like->created_at = date('Y-m-d H:i:s');
        R::store($like);
        $action = 'liked';
    }
    
    $totalLikes = R::count('likes', 'photo_id = ?', [$photoId]);
    
    echo json_encode([
        'status' => 'success',
        'action' => $action,
        'newLikeCount' => $totalLikes,
        'message' => $action === 'liked' ? 'Лайк добавлен!' : 'Лайк удален!'
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Server error'
    ]);
}
?>