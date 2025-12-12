<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Фототека - Вход</title>
     <link rel="stylesheet" href="/static/main.css">
</head>
<body>
    <!-- require('./templates/clue_for_login.tpl'); -->

    <?php require('./templates/clue_for_login.tpl');?>
 
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




<script>
document.addEventListener('DOMContentLoaded', function() {
    // Функционал копирования
    const copyButtons = document.querySelectorAll('.copy-btn');
    
    copyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const credentialValue = this.closest('.credential-value');
            const textToCopy = credentialValue.getAttribute('data-text');
            
            // Копируем в буфер обмена
            navigator.clipboard.writeText(textToCopy).then(() => {
                // Показываем состояние "скопировано"
                this.classList.add('copied');
                
                // Возвращаем обратно через 2 секунды
                setTimeout(() => {
                    this.classList.remove('copied');
                }, 2000);
            }).catch(err => {
                console.error('Ошибка копирования: ', err);
                // Fallback для старых браузеров
                const textArea = document.createElement('textarea');
                textArea.value = textToCopy;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                
                this.classList.add('copied');
                setTimeout(() => {
                    this.classList.remove('copied');
                }, 2000);
            });
        });
    });
    
    // Функционал закрытия подсказки
    const closeButton = document.querySelector('.hint-close');
    if (closeButton) {
        closeButton.addEventListener('click', function() {
            const hintBlock = this.closest('.test-user-hint');
            hintBlock.style.opacity = '0';
            hintBlock.style.transform = 'translateY(-10px)';
            hintBlock.style.height = '0';
            hintBlock.style.margin = '0';
            hintBlock.style.padding = '0';
            hintBlock.style.overflow = 'hidden';
            
            setTimeout(() => {
                hintBlock.style.display = 'none';
            }, 300);
            
            // Сохраняем в localStorage, чтобы не показывать снова
            if (typeof(Storage) !== 'undefined') {
                localStorage.setItem('testHintClosed', 'true');
            }
        });
    }
    
    // Проверяем, не закрывал ли пользователь подсказку ранее
    if (typeof(Storage) !== 'undefined' && localStorage.getItem('testHintClosed') === 'true') {
        const hintBlock = document.querySelector('.test-user-hint');
        if (hintBlock) {
            hintBlock.style.display = 'none';
        }
    }
});
</script>

</body>
</html>