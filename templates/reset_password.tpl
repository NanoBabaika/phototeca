<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Фототека - Вход</title>
     <link rel="stylesheet" href="/static/main.css">
</head>
<body>
<div class="password-reset-container">
    <div class="reset-card">
        <h2 class="reset-title">🔄 Установка нового пароля</h2>
        <p class="reset-subtitle">
            Придумайте новый надежный пароль для вашего аккаунта.
        </p>

        <form class="reset-form" method="POST" action="reset_password.php">
            <!-- Скрытое поле для токена (если используем GET параметр token) -->
            <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">

            <div class="form-group">
                <label for="new_password" class="form-label">Новый пароль</label>
                <input type="password" 
                       id="new_password" 
                       name="new_password" 
                       class="form-input" 
                       placeholder="Введите новый пароль"
                       minlength="6"
                       required>
                <div class="form-hint">
                    Пароль должен содержать минимум 6 символов
                </div>
            </div>

            <div class="form-group">
                <label for="confirm_password" class="form-label">Подтвердите пароль</label>
                <input type="password" 
                       id="confirm_password" 
                       name="confirm_password" 
                       class="form-input" 
                       placeholder="Повторите новый пароль"
                       minlength="6"
                       required>
                <div class="form-hint">
                    Оба поля должны совпадать
                </div>
            </div>

            <button type="submit" class="submit-btn" name="submit">
                🔄 Обновить пароль
            </button>

            <div class="password-requirements">
                <h4>Требования к паролю:</h4>
                <ul>
                    <li>Минимум 6 символов</li>
                    <li>Рекомендуется использовать буквы, цифры и специальные символы</li>
                    <li>Не используйте простые пароли (123456, qwerty и т.д.)</li>
                </ul>
            </div>
        </form>
    </div>
</div>

</body>
</html>