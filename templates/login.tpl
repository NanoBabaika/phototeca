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
    const testUserHint = document.getElementById('testUserHint');
    const hintToggle = document.getElementById('hintToggle');
    const expandHint = document.getElementById('expandHint');
    const hintContent = document.getElementById('hintContent');
    const hintCollapsed = document.getElementById('hintCollapsed');
    const toggleIcon = hintToggle.querySelector('.toggle-icon');
    
    // Проверяем сохраненное состояние
    const isHintCollapsed = localStorage.getItem('testHintCollapsed') === 'true';
    
    // Устанавливаем начальное состояние
    if (isHintCollapsed) {
        collapseHint();
    } else {
        expandHintFunc();
    }
    
    // Функция сворачивания подсказки
    function collapseHint() {
        testUserHint.classList.add('collapsed');
        toggleIcon.textContent = '+';
        hintToggle.setAttribute('aria-label', 'Развернуть подсказку');
        hintToggle.title = 'Развернуть подсказку';
        localStorage.setItem('testHintCollapsed', 'true');
    }
    
    // Функция разворачивания подсказки
    function expandHintFunc() {
        testUserHint.classList.remove('collapsed');
        toggleIcon.textContent = '−';
        hintToggle.setAttribute('aria-label', 'Свернуть подсказку');
        hintToggle.title = 'Свернуть подсказку';
        localStorage.setItem('testHintCollapsed', 'false');
    }
    
    // Переключение по клику на кнопку в заголовке
    hintToggle.addEventListener('click', function() {
        if (testUserHint.classList.contains('collapsed')) {
            expandHintFunc();
        } else {
            collapseHint();
        }
    });
    
    // Разворачивание по клику на кнопку в свернутом блоке
    expandHint.addEventListener('click', expandHintFunc);
    
    // Функционал копирования (оставляем без изменений)
    const copyButtons = document.querySelectorAll('.copy-btn');
    
    copyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const credentialValue = this.closest('.credential-value');
            const textToCopy = credentialValue.getAttribute('data-text');
            
            navigator.clipboard.writeText(textToCopy).then(() => {
                this.classList.add('copied');
                
                setTimeout(() => {
                    this.classList.remove('copied');
                }, 2000);
            }).catch(err => {
                console.error('Ошибка копирования: ', err);
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
    
    // Дополнительно: закрытие по клику вне блока (опционально)
    document.addEventListener('click', function(event) {
        if (!testUserHint.contains(event.target) && 
            !testUserHint.classList.contains('collapsed') &&
            event.target !== hintToggle) {
            // Автоматически сворачиваем при клике вне блока
            // collapseHint();
        }
    });
});
</script>

</body>
</html>