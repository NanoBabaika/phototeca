    <div class="profile-container">
        <!-- Шапка профиля -->
        <div class="profile-header">
            <div class="avatar">
                <?php if (!empty($user->avatar)) : ?>
                    <img class="avatar__img--profile" src="./uploads/avatars/<?= $user->avatar ?>" alt="Аватарка" />
                <?php else : ?>
                    <img class="avatar__img--profile" src="./uploads/avatars/01.jpeg" alt="Здесь могла быть Ваша аватарка" />
                <?php endif; ?>
            </div>
            <h1 class="user-name"><?= htmlspecialchars($user['name'] ?? 'Пользователь') ?></h1>
        </div>

        <!-- Основное содержимое -->
        <div class="profile-content">
            <!-- Блок с информацией -->
            <div class="info-section">
                <h2 class="section-title">📊 Основная информация</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <div class="info-label">Имя пользователя</div>
                        <div class="info-value"><?= htmlspecialchars($user['name'] ?? 'Не указано') ?></div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Email</div>
                        <div class="info-value"><?= htmlspecialchars($user['email'] ?? 'Не указан') ?></div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Город</div>
                        <div class="info-value <?= empty($user['city']) ? 'empty-value' : '' ?>">
                            <?= !empty($user['city']) ? htmlspecialchars($user['city']) : 'Не указан' ?>
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Контактный Телефон</div>
                        <div class="info-value <?= empty($user['phone']) ? 'empty-value' : '' ?>">
                            <?= !empty($user['phone']) ? htmlspecialchars($user['phone']) : 'Не указан' ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Статистика -->
            <div class="stats-section">
                <h2 class="section-title">📈 Статистика фото</h2>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number"><?= $stats[0] ?? 0 ?></div>
                        <div class="stat-label">Всего фото</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= $stats[2] ?? 0 ?></div>
                        <div class="stat-label">Загружено сегодня</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= $filesSize['readable'] ?? 0 ?></div>
                        <div class="stat-label">Общий размер</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= $stats[1] ?? '—' ?></div>
                        <div class="stat-label">Последняя загрузка</div>
                    </div>
                </div>
            </div>

            <!-- Кнопки действий -->
            <div class="action-buttons">
                <a href="./profile-edit.php" class="btn btn-primary">
                    ✏️ Редактировать профиль
                </a>
                <a href="./gallery.php" class="btn btn-success">
                    🖼️ Перейти в галерею
                </a>
            </div>

            <!-- Быстрые действия -->
            <!-- <div class="quick-actions mt-20">
                <h3 class="actions-title">🚀 Быстрые действия</h3>
                <div class="action-buttons">
                    <button onclick="showPopular()" class="btn btn-secondary">🔥 Популярные</button>
                    <button onclick="exportData()" class="btn btn-secondary">📁 Экспорт данных</button>
                </div>
            </div> -->
        </div>
    </div>

    <!-- <script>
        function showPopular() {
            alert('Система популярности постов будет реализована позже.');
            // Здесь будет логика экспорта данных пользователя
        }
        

        function exportData() {
            alert('Функция экспорта данных будет реализована позже!');
            // Здесь будет логика экспорта данных пользователя
        }
    </script> -->
</body>
</html>