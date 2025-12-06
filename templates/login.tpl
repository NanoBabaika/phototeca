<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Фототека - Вход</title>
     <link rel="stylesheet" href="/static/main.css">
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>📸 Фототека</h1>
            <p>Ваше личное фотохранилище</p>
        </div>
 
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password">
            </div>

            <button type="submit" class="btn" name="login">Войти</button>
        </form>

        <div class="links">
            <a href="/register.php">Создать аккаунт</a>
            <!-- <a id = "forgot-password" href="forgot-password.php">Забыли пароль?</a> -->
            <a id = "forgot-password" href="forgot-password.php">Забыли пароль?</a>

        </div>
    </div>

</body>
</html>