<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Фототека - Восстановление пароля</title>
    <link rel="stylesheet" href="/static/main.css">
</head>
<body>
<div class="password-reset-container">
    <div class="reset-card">
        <h2 class="reset-title">🔄 Установка нового пароля</h2>
        
 
        
        <?php if (isset($reset_request) && $reset_request->expires_at > date('Y-m-d H:i:s')): ?>
            <p class="reset-subtitle">
                Придумайте новый надежный пароль для вашего аккаунта.
            </p>

            <form class="reset-form" method="POST" action="reset-password.php?token=<?= htmlspecialchars($_GET['token'] ?? '') ?>">
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

                <button type="submit" class="submit-btn-forgot" name="submit">
                    🔄 Обновить пароль
                </button>
            </form>
        <?php else: ?>
            <div class="error-message">
                <p>Ссылка недействительна или истекла.</p>
                <p><a href="forgot-password.php">Запросить новую ссылку</a></p>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>