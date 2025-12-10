<div class="users-container">
    <!-- Заголовок страницы -->
    <div class="page-header">
        <h1 class="page-title">👥 Сообщество пользователей</h1>
        <p class="page-subtitle">Откройте для себя талантливых авторов и их работы</p>
    </div>

    <!-- Поиск и фильтры -->
    <div class="search-section">
        <form class="search-form" method="GET" action="users.php">
            <div class="search-input-group">
                <input type="text" 
                       name="search_query" 
                       class="search-input" 
                       placeholder="Поиск по имени или городу..."
                       value="<?= htmlspecialchars($_GET['search_query'] ?? '') ?>">
                
                <!-- Скрытое поле для типа поиска -->
                <input type="hidden" name="search_type" id="searchTypeInput" value="<?= htmlspecialchars($_GET['search_type'] ?? 'both') ?>">
                
                <div class="search-buttons">
                    <button type="button" 
                            class="filter-btn <?= ($_GET['search_type'] ?? 'both') === 'both' ? 'active' : '' ?>" 
                            data-type="both">
                        🔍 Везде
                    </button>
                    <button type="button" 
                            class="filter-btn <?= ($_GET['search_type'] ?? 'both') === 'name' ? 'active' : '' ?>" 
                            data-type="name">
                        👤 По имени
                    </button>
                    <button type="button" 
                            class="filter-btn <?= ($_GET['search_type'] ?? 'both') === 'city' ? 'active' : '' ?>" 
                            data-type="city">
                        🏙️ По городу
                    </button>
                    <button type="submit" class="search-btn">
                        🔎 Искать
                    </button>
                    
                    <!-- Кнопка сброса поиска -->
                    <?php if (isset($_GET['search_query']) && !empty($_GET['search_query'])): ?>
                    <a href="users.php" class="search-clear-btn">
                        ❌ Сбросить
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
        
        <!-- Информация о поиске -->
        <?php if (isset($_GET['search_query']) && !empty($_GET['search_query'])): ?>
        <div class="search-info">
            <p>
                <?php 
                    $searchTypes = [
                        'both' => 'всюду',
                        'name' => 'по имени',
                        'city' => 'по городу'
                    ];
                    $currentType = $_GET['search_type'] ?? 'both';
                ?>
                Поиск <?= $searchTypes[$currentType] ?>: 
                <strong>"<?= htmlspecialchars($_GET['search_query']) ?>"</strong>
                | Найдено: <strong><?= count($users) ?></strong> пользователей
            </p>
        </div>
        <?php endif; ?>
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
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">👥</div>
                <?php if (isset($_GET['search_query']) && !empty($_GET['search_query'])): ?>
                    <h3>Пользователи не найдены</h3>
                    <p>Попробуйте изменить параметры поиска</p>
                <?php else: ?>
                    <h3>Пока нет других пользователей</h3>
                    <p>Пригласите друзей в фототеку!</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.querySelector('.search-form');
    const searchTypeInput = document.getElementById('searchTypeInput');
    const searchInput = document.querySelector('input[name="search_query"]');
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    // Обработка кнопок типа поиска
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Убираем активный класс у всех кнопок
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Добавляем активный класс текущей кнопке
            this.classList.add('active');
            
            // Устанавливаем тип поиска
            searchTypeInput.value = this.dataset.type;
            
            // Если есть текст в поле поиска, сразу отправляем форму
            if (searchInput.value.trim()) {
                searchForm.submit();
            }
        });
    });
    
    // Автопоиск при вводе (с задержкой)
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            if (this.value.trim()) {
                searchForm.submit();
            } else if (this.value === '') {
                // Если поле очистили, сбрасываем поиск
                window.location.href = 'users.php';
            }
        }, 500); // Задержка 500мс
    });
});
</script>