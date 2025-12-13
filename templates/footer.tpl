        </div>
    </main>

    <?php $totalUsers = R::count('users');?>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-info">
                    <p>&copy; 2025 Фототека. Все права защищены.</p>
                    <p>Сделано с ❤️ для хранения воспоминаний.</p>
                </div>
                <div class="footer-links">
                    <a href="#" class="footer-link">О проекте</a>
                    <a href="#" class="footer-link">Помощь</a>
                    <a href="#" class="footer-link">Контакты</a>
                </div>
                <div class="footer-stats">
                    <p>📊 Пользователей: <span id="user-count"><?php echo $totalUsers ?? '0'; ?></span></p>
                </div>
            </div>
        </div>
    </footer>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // const burgerMenu = document.getElementById('burgerMenu');
            const mobileMenu = document.getElementById('mobileMenu');
            const menuOverlay = document.querySelector('.mobile-menu-overlay');
            const menuClose = document.getElementById('menuClose');
            
            // Функция открытия/закрытия меню
            function toggleMenu() {
                burgerMenu.classList.toggle('active');
                mobileMenu.classList.toggle('active');
                menuOverlay.classList.toggle('active');
                document.body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : '';
            }
            
            // Открытие меню по клику на бургер
            burgerMenu.addEventListener('click', toggleMenu);
            
            // Закрытие меню по клику на кнопку закрытия
            menuClose.addEventListener('click', toggleMenu);
            
            // Закрытие меню по клику на overlay
            menuOverlay.addEventListener('click', toggleMenu);
            
            // Закрытие меню по клику на ссылку в меню
            const mobileLinks = document.querySelectorAll('.mobile-nav-link');
            mobileLinks.forEach(link => {
                link.addEventListener('click', function() {
                    // Не закрываем сразу, даем время на переход
                    setTimeout(toggleMenu, 300);
                });
            });
            
            // Закрытие меню по нажатию Escape
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && mobileMenu.classList.contains('active')) {
                    toggleMenu();
                }
            });
     
});
</script>

        
</body>
</html>