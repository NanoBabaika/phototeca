<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Фототека - Вход</title>
     <link rel="stylesheet" href="/static/main.css">
</head>
<body>
<div class="password-recovery-container">
    <div class="recovery-card">
        <h2 class="recovery-title">🔐 Восстановление пароля</h2>
        <p class="recovery-subtitle">
            Укажите адрес электронной почты, который вы использовали при регистрации.
            Мы отправим вам ссылку для восстановления пароля.
        </p>

        <form class="recovery-form" method="POST" action="forgot-password.php">
            <div class="form-group">
                <label for="email" class="form-label">Email адрес</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       class="form-input" 
                       placeholder="ваш@email.com"
                       >
                <div class="form-hint">
                    На этот email будет отправлена ссылка для восстановления пароля
                </div>
            </div>

            <button type="submit" class="submit-btn" name="submit">
                📧 Отправить ссылку
            </button>

            <div class="back-to-login">
                <a href="login.php" class="back-link">← Вернуться к входу</a>
            </div>
        </form>

        <div class="recovery-info">
            <h3>📌 Порядок действий по восстановлению пароля</h3>
            <ol class="steps-list">
                <li>Проверьте вашу почту (не забудьте папку "Спам")</li>
                <li>Перейдите по ссылке из письма</li>
                <li>Придумайте новый пароль</li>
                <li>Войдите в аккаунт с новым паролем</li>
            </ol>
        </div>
    </div>
</div>

</body>
</html>