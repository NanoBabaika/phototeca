<div class="profile-edit-container">
    <h2>Редактирование профиля</h2>
    
    <form method="POST" action="profile-edit.php" enctype="multipart/form-data" class="profile-form">
        <!-- Блок с текущим аватаром -->
        <div class="avatar-section">
            <div class="current-avatar">

                <?php if (!empty($user->avatar)) : ?>
                    <img class="avatar__img" src="./uploads/avatars/<?= $user->avatar ?>" alt="Аватарка" />
                <?php else : ?>
                    <img class="avatar__img" src="./uploads/avatars/01.jpeg" alt="Аватарка" />
                <?php endif; ?>

                <!-- <img src="./uploads/avatars/01.jpeg" alt="Текущий аватар" id="avatar-preview"> -->
            </div>
            <div class="avatar-upload">
                <label for="avatar">Загрузить новый аватар:</label>
                <input type="file" name="avatar" id="avatar" accept="image/*">
                <small>Рекомендуемый размер: 200x200px</small>
                 <button name="delete-avatar" type="submit" class="delete-avatar">Удалить аватар</button>
            </div>
        </div>

        <!-- Основные данные -->
        <div class="form-group">
            <label for="username">Имя пользователя:</label>
            <input type="text" id="username" name="name" value="<?=htmlspecialchars($user->name)?>">
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?=htmlspecialchars($user->email)?>">
        </div>

        <div class="form-group">
            <label for="city">Город:</label>
            <input type="text" id="city" name="city" value="<?= !empty($user['city']) ? htmlspecialchars($user['city']) : 'Не указан' ?>">
        </div>

        <div class="form-group">
            <label for="phone">Телефон:</label>
            <input type="tel" id="phone" name="phone" value="<?= !empty($user['phone']) ? htmlspecialchars($user['phone']) : 'Не указан' ?>">
        </div>

        <!-- Смена пароля (опционально) -->
        <div class="password-section">
            <h3>Смена пароля</h3>
            <div class="form-group">
                <label for="current_password">Текущий пароль:</label>
                <input type="password" id="current_password" name="current_password">
            </div>
            <div class="form-group">
                <label for="new_password">Новый пароль:</label>
                <input type="password" id="new_password" name="new_password">
            </div>
        </div>

        <div class="form-actions">
            <button name="edit-profile" type="submit" class="edit-profile--btn">Сохранить изменения</button>
            <a href="profile.php" class="btn-secondary">Отмена</a>
        </div>
    </form>


    <!-- УДАЛЕНИЕ ПРОФИЛЯ -->
    <div class="danger-zone">
        <div class="danger-content">
            <div class="danger-text">
                <h4>Удаление профиля</h4>
                <p>После удаления профиля все ваши данные будут безвозвратно удалены. Это действие нельзя отменить.</p>
            </div>
            <button id="delete-profile-btn" type="button" class="btn-danger">
                Удалить профиль
            </button>
        </div>
    </div>

</div>


<script>
    let userId = <?php echo json_encode($id); ?>;
    console.log('пользователь которого нужно удалить', userId);

    const btn_profile_delete = document.getElementById('delete-profile-btn');
    console.log(btn_profile_delete);

    btn_profile_delete.addEventListener('click', function(e) {
        // Останавливаем стандартное поведение формы
        e.preventDefault();
            
        // Спрашиваем подтверждение
        const isConfirmed = confirm('Вы уверены, что хотите удалить профиль? Нам будет Вас не хватать :(');
            
        if (isConfirmed) {
            console.log("отправка формы!");

            fetch('./api/delete_profile.php', {
                method: 'POST', 
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                // Передаем параметр, который скажет PHP, какую функцию выполнять
                body: 'user_id=' + encodeURIComponent(userId) // <- убрана точка с запятой
            })
            .then(response => response.text()) 
            .then(data => {
                // Обрабатываем ответ от сервера и выводим его
                console.log('Ответ от сервера:', data);
                // Предполагаем, что при успешном удалении сервер возвращает 'success'
                if (data.trim() === 'success') {
                    alert('Профиль успешно удален');
                    window.location.href = 'login.php';  
                    } else {
                    alert('Ошибка: ' + data);
                }
            })
            .catch(error => {
                console.error('Произошла ошибка:', error);
                alert('Произошла ошибка при выполнении запроса.');
            });
        }
    });
</script>

 