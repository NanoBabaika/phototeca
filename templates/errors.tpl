<!-- ошибки -->
<?php if (!empty($_SESSION['errors'])): ?>
    <div class="error-message">
        <?php foreach ($_SESSION['errors'] as $error): ?>
            <p> <?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
        <?php $_SESSION['errors'] = []; ?>
    </div>
<?php endif; ?>


<!-- Блок для успешных сообщений -->
<?php if(!empty($_SESSION['success'])): ?>
    <div class="success-message">
        <?php foreach($_SESSION['success'] as $message):?>
            <p><?=$message?></p>
        <?php endforeach;?>
    </div>
    <?php $_SESSION['success'] = []; ?>
<?php endif; ?>



<!-- Написать обработку ошибок на всех страницах.  -->
<!-- Ошибки хранить в сессии. -->