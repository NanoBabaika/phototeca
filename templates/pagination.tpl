<?php if (isset($paginationData) && $paginationData['total_pages'] > 1): ?>
<div class="pagination-container">
    <nav class="pagination-nav" aria-label="Page navigation">
        <ul class="pagination">
            <!-- Кнопка "Назад" -->
            <li class="page-item <?= ($paginationData['current_page'] == 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $paginationData['current_page'] - 1 ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            <!-- Первая страница -->
            <?php if ($paginationData['current_page'] > 3): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=1">1</a>
                </li>
                <?php if ($paginationData['current_page'] > 4): ?>
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Страницы вокруг текущей -->
            <?php for ($i = max(1, $paginationData['current_page'] - 2); $i <= min($paginationData['total_pages'], $paginationData['current_page'] + 2); $i++): ?>
                <li class="page-item <?= ($i == $paginationData['current_page']) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <!-- Последняя страница -->
            <?php if ($paginationData['current_page'] < $paginationData['total_pages'] - 2): ?>
                <?php if ($paginationData['current_page'] < $paginationData['total_pages'] - 3): ?>
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                <?php endif; ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $paginationData['total_pages'] ?>"><?= $paginationData['total_pages'] ?></a>
                </li>
            <?php endif; ?>

            <!-- Кнопка "Вперед" -->
            <li class="page-item <?= ($paginationData['current_page'] == $paginationData['total_pages']) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $paginationData['current_page'] + 1 ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
        
        <!-- Информация о текущем положении -->
        <div class="pagination-info">
            Страница <?= $paginationData['current_page'] ?> из <?= $paginationData['total_pages'] ?>
        </div>
    </nav>
</div>
<?php endif; ?>