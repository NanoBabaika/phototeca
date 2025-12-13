<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Фототека - Ваше фотохранилище</title>
    <link rel="stylesheet" href="/static/main.css">
</head>
<body>


    <!-- Мобильное меню -->
    <div class="mobile-menu-overlay"></div>
    
    <div class="mobile-menu" id="mobileMenu">
        <button class="menu-close" id="menuClose">×</button>
        
        <div class="mobile-user-info">
            <div class="mobile-user-avatar">
                👤
            </div>
            <div class="mobile-user-name">Гость</div>
            <div class="mobile-user-email">Войдите в аккаунт</div>
        </div>
        
        <ul class="mobile-nav">
            <li class="mobile-nav-item">
                <a href="profile.php" class="mobile-nav-link">
                    <span>👤</span> Профиль
                </a>
            </li>
            <li class="mobile-nav-item">
                <a href="gallery.php" class="mobile-nav-link">
                    <span>🖼️</span> Моя галерея
                </a>
            </li>
            <li class="mobile-nav-item">
                <a href="users.php" class="mobile-nav-link">
                    <span>👥</span> Пользователи
                </a>
            </li>
            <li class="mobile-nav-item">
                <a href="logout.php" class="mobile-nav-link logout">
                    <span>🚪</span> Выход
                </a>
            </li>
        </ul>
        
        <div class="menu-footer">
            📸 Фототека © 2024
        </div>
    </div>



    <header class="header">
        <div class="header__container">
            <div class="header-content">
                <div class="logo">
                    <a class="logo-link" href="./index.php">
                        <h1>📸 Фототека</h1>
                    </a>
                 </div>


                <!-- Бургер-меню для мобильных -->
                <button class="burger-menu" id="burgerMenu">
                    <span class="burger-line"></span>
                    <span class="burger-line"></span>
                    <span class="burger-line"></span>
                </button>

                
                <nav class="nav">
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="profile.php" class="nav-link">
                                👤 Профиль
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="gallery.php" class="nav-link">
                                🖼️ Моя галерея
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="users.php" class="nav-link">
                                👥 Пользователи
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="logout.php" class="nav-link logout">
                                🚪 Выход
                            </a>
                        </li>
                    </ul>
                </nav>
 
            </div>
        </div>
    </header>

    <main class="main">
        <div class="container ">