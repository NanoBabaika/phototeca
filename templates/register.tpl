<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Фототека - регистрация</title>
     <link rel="stylesheet" href="/static/main.css">
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>📸 Фототека</h1>
            <p>Ваше личное фотохранилище фотографий</p>
            <p>Страница регистрации</p>
        </div>


        <form method="POST" action="register.php">
            <div class="form-group mt-20">
                <label for="name">Имя или Никнейм:</label>
                <input type="text" id="name" name="name" 
                       value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password">
            </div>

            <button name="register" type="submit" class="btn" name="login">Зарегистироваться</button>
        </form>

     </div>
</body>
</html>