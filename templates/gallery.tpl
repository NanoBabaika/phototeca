<div class="gallery-container">
    <?php if($id == $user->id): ?>
    <form action="gallery.php" method="POST" class="download__files mt-20" enctype="multipart/form-data">
        <input name="file" class="btn btn-primary--upload" type="file" multiple>
        <button name="set-file" class="btn btn-secondary-upload">📤 Загрузить фото</button>
    </form>
    
    <h1 class="gallery-title mt-20">Моя галерея</h1>
    <?php endif; ?>
    
    <div class="photos-grid">
        <?php if (!empty($paginationData['items'])): ?>
            <?php foreach ($paginationData['items'] as $photo): ?>
                <?php 
                    $fileName = htmlspecialchars($photo['filename']);
                    // Используем миниатюру для галереи
                    $thumbPath = "./uploads/photos/" . $id . "/thumbs/" . $fileName;
                    $originalPath = "./uploads/photos/" . $id . "/" . $fileName;
                ?>
                
                <div class="photo-card">
                    <div class="photo-image-container">
                        <!-- Показываем миниатюру в галерее -->
                        <img src="<?php echo file_exists($thumbPath) ? $thumbPath : $originalPath; ?>" 
                            alt="<?= htmlspecialchars($photo['original_name']) ?>" 
                            class="photo-img"
                            data-original="<?= $originalPath ?>">
                        
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
                        <div class="photo-size">
                            Размер: <?= round($photo['file_size'] / 1024 / 1024, 2) ?> MB
                        </div>
                    </div>
                    
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
                        
                        <button class="view-original" data-src="<?= $originalPath ?>">
                            <span class="original-icon">🔍</span>
                            Оригинал
                        </button>
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
    
    <!-- Модальное окно для просмотра оригинала -->
    <div class="modal-overlay" id="imageModal">
        <div class="modal-content">
            <button class="modal-close" id="closeModal">×</button>
            <img src="" alt="" id="modalImage" class="modal-image">
            <div class="modal-info">
                <div id="imageName"></div>
                <div id="imageSize"></div>
            </div>
        </div>
    </div>
    
    <script>
        let totalLikes = <?php echo isset($totalLikes) ? json_encode($totalLikes) : '0'; ?>;
        let likedIds = <?php echo isset($likedIds) ? json_encode($likedIds) : '[]'; ?>;
    </script>
    <script src="./js/likes.js"></script>
    <script src="./js/gallery.js"></script>
</div>