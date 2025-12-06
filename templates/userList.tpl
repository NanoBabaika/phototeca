    <div class="users-container">
        <!-- Заголовок страницы -->
        <div class="page-header">
            <h1 class="page-title">👥 Сообщество пользователей</h1>
            <p class="page-subtitle">Откройте для себя талантливых авторов и их работы</p>
        </div>

        <!-- Поиск и фильтры -->
        <div class="search-section">
            <form class="search-form" method="GET" action="">
                <input type="text" 
                       name="search" 
                       class="search-input" 
                       placeholder="Поиск по имени или городу..."
                       value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button type="submit" class="search-btn">🔍 Найти</button>
            </form>
            
            <!-- <div class="filters">
                <button class="filter-btn active">Все</button>
                <button class="filter-btn">С фото</button>
                <button class="filter-btn">Новые</button>
                <button class="filter-btn">Популярные</button>
            </div> -->
        </div>

        <!-- Сетка пользователей -->
        <div class="users-grid">
            <?php if (!empty($paginationData['items'])): ?>
                <?php foreach ($paginationData['items'] as $user): ?>
                    <a href="singleUser.php?user_id=<?= $user['id'] ?>" class="user-card">
                        <div class="user-avatar">
                            <?php if (!empty($user['avatar'])): ?>
                                <img src="./uploads/avatars/<?= htmlspecialchars($user['avatar']) ?>" alt="<?= htmlspecialchars($user['name']) ?>">
                            <?php else: ?>
                                👤
                            <?php endif; ?>
                        </div>
                        
                        
                        <div class="user-name">
                            <?= isset($user['name']) && !empty($user['name']) ? htmlspecialchars($user['name']) : 'Имя не указано' ?>
                        </div>
                        
                        <?php if (!empty($user['city'])): ?>
                            <div class="user-city">
                                📍 <?= htmlspecialchars($user['city']) ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="user-stats">
                            <div class="stat">
                                <div class="stat-number"><?= $user['photos_count'] ?? 0 ?></div>
                                <div class="stat-label">Фото</div>
                            </div>
                            <!-- <div class="stat">
                                <div class="stat-number"><?= $user['followers'] ?? 0 ?></div>
                                <div class="stat-label">Подписчиков</div>
                            </div> -->
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">👥</div>
                    <h3>Пользователи не найдены</h3>
                    <p>Попробуйте изменить параметры поиска</p>
                </div>
            <?php endif; ?>
        </div>
    </div>