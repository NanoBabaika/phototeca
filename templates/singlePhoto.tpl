<div class="single-photo-container">
    <!-- Хлебные крошки -->
    <nav class="breadcrumbs">
        <a href="gallery.php">Галерея</a> &gt; 
        <span>Просмотр фотографии</span>
    </nav>

    <!-- Основное фото -->
    <div class="photo-fullview">
        <img src="<?= $photoPath ?>" 
             alt="<?= htmlspecialchars($photo['original_name']) ?>" 
             class="full-photo">
    </div>

    <!-- Информация о фото -->
    <div class="photo-meta">
        <h2 class="photo-title"><?= htmlspecialchars($photo['original_name']) ?></h2>
        <div class="photo-details">
            <span class="uploader">Автор: <?=$autorName ?></span>
            <span class="upload-date">Загружено: <?= date('d.m.Y H:i', strtotime($photo['created_at'])) ?></span>
        </div>
    </div>

    <!-- Блок действий (лайки) -->  
    <div class="photo-actions-full">
        <button id="like_btn"class="like-btn-full" data-photo-id="<?=$photosId?>">
            <span class="like-icon">❤️</span>
            <span id="likes-count"><?= $totalLikes ?></span>
            <!-- <span class="likes-count" id="likes-count">0</span> -->
            <span class="like-text">Нравится</span>
        </button>
    </div>

    <!-- Блок комментариев -->
    <div class="comments-section">
        <h3 class="comments-title">Комментарии (<?= $count_comments ?? 0 ?>)</h3>
        
        <!-- Список комментариев -->
        <div class="comments-list">
            <?php if (!empty($comments)): ?>
                <?php foreach ($comments as $comment): ?>

                    <div class="comment-item">
                        <div class="comment-header">
                            <span class="comment-author"><?=htmlspecialchars($comment['user_name']) ?></span>
                            <span class="comment-date"><?= date('d.m.Y H:i', strtotime($comment['created_at'])) ?></span>
                        </div>
                        <div class="comment-text">
                            <?= htmlspecialchars($comment['comment_text']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-comments">
                    Пока нет комментариев. Будьте первым!
                </div>
            <?php endif; ?>
        </div>

        <!-- Форма добавления комментария -->
        <form id="single_photo_form" class="comment-form" method="POST" action="singlePhoto.php?photo_id=<?= $photosId ?>">
            <div class="form-group">
                <textarea name="comment_text" 
                          class="comment-textarea" 
                          placeholder="Оставьте ваш комментарий..." 
                          rows="4"
                          ></textarea>
            </div>
            <div class="form_photo_btns">
                <button name ="submit_comment" type="submit" class="submit-comment-btn">
                    💬 Добавить комментарий
                </button>

                <!-- Проверяем владелец ли фото зашел на страницу -->
                <?php if($userId === $autorId): ?>
                    <!-- через js не приходит имя кнопки поэтому отправляем скрытый атрибут -->
                 <input type="hidden" name="delete-photo" value="1"> 
                 <button id = "delete_photo" name ="delete-photo" type="submit" class="btn-danger">
                    Удалить фотографию
                </button>
                <?php endif; ?>

            </div>

        </form>
    </div>
    <script>  
        const deleteBtn = document.getElementById('delete_photo');
        const singlePhotoForm = document.getElementById('single_photo_form');

        deleteBtn.addEventListener('click', function(e) {
            // Останавливаем стандартное поведение формы
            e.preventDefault();
                
            // Спрашиваем подтверждение
            const isConfirmed = confirm('Вы уверены, что хотите удалить эту фотографию? Это действие необратимо!');
                
            if (isConfirmed) {
                // Если подтвердили - отправляем форму
                singlePhotoForm.submit();
            }
        });
        
    </script>

    <script>
        let totalLikes = <?php echo json_encode($totalLikes); ?>;
        let userId = <?php echo json_encode($userId); ?>;
    </script>
    <script src="./js/likes.js"></script>
</div>