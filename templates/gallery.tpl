<div class="gallery-container">
    <!-- форма для загрузки файлов -->
    <?php if($id == $user->id): ?>
    <form action="gallery.php" method="POST" class="download__files mt-20" enctype="multipart/form-data">
        <input name="file" class="btn btn-primary--upload" type="file" multiple>
        <button name="set-file" class="btn btn-secondary-upload">📤 Загрузить фото</button>
    </form>
    
    <h1 class="gallery-title mt-20">Моя галерея</h1>
    <?php endif; ?>
    
    <!-- Показ всех фото пользователя -->
    <div class="photos-grid">
        <?php if (!empty($paginationData['items'])): ?>
            <?php foreach ($paginationData['items'] as $photo): ?>
                <?php $fileName = htmlspecialchars($photo['filename']); ?>
                
                <div class="photo-card">
                    <div class="photo-image-container">
                        <img src="./uploads/photos/<?php echo $id . '/' . $fileName; ?>" 
                            alt="<?= htmlspecialchars($photo['original_name']) ?>" 
                            class="photo-img">
                        
                        <!-- Ссылка на страницу фото -->
                        <a href="singlePhoto.php?photo_id=<?= $photo['id'] ?>" class="photo-overlay">
                            <span class="view-full">👁️ Подробнее</span>
                        </a>
                    </div>
                    
                    <div class="photo-info">
                        <div class="photo-name">
                            Название фото: <?= htmlspecialchars($photo['original_name']) ?>
                        </div>
                        <div class="upload-date">
                            Дата загрузки: <?= date('d.m.Y H:i', strtotime($photo['created_at'])) ?>
                        </div>
                    </div>
                    
                    <!-- Блок лайков и комментариев -->
                    <div class="photo-actions">
                        <button class="like-btn" 
                                data-photo-id="<?= $photo['id']?>" 
                                data-is-liked="<?= $photo['is_liked'] ? 'true' : 'false' ?>">
                            <span class="like-icon">❤️</span>
                            <span class="likes-count"><?= $stats['likes'][$photo['id']] ?? 0 ?></span>
                        </button>
                        
                        <a href="singlePhoto.php?photo_id=<?= $photo['id'] ?>" class="comments-link">
                            <span class="comment-icon">💬</span>
                            <span class="comments-count"><?= $stats['comments'][$photo['id']] ?? 0 ?></span>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-gallery">
                <div class="empty-gallery-icon">🖼️</div>   
                <h3>В галерее пока нет фото</h3>
            </div>
        <?php endif; ?>
    </div> 
    <script>
        let totalLikes = <?php echo isset($totalLikes) ? json_encode($totalLikes) : '0'; ?>;
        let likedIds = <?php echo isset($likedIds) ? json_encode($likedIds) : '[]'; ?>;
    </script>
    <script src="./js/likes.js"></script>
</div>