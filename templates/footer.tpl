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

        
</body>
</html>