<?php if (isset($paginationData) && $paginationData['total_pages'] > 1): ?>
<div class="pagination">
    <?php if ($paginationData['current_page'] > 1): ?>
        <a href="?page=<?= $paginationData['current_page'] - 1 ?><?= isset($_GET['search_query']) ? '&search_query=' . urlencode($_GET['search_query']) . '&search_type=' . urlencode($_GET['search_type'] ?? 'both') : '' ?>" 
           class="page-link prev">
            ← Назад
        </a>
    <?php endif; ?>
    
    <?php for ($i = 1; $i <= $paginationData['total_pages']; $i++): ?>
        <a href="?page=<?= $i ?><?= isset($_GET['search_query']) ? '&search_query=' . urlencode($_GET['search_query']) . '&search_type=' . urlencode($_GET['search_type'] ?? 'both') : '' ?>" 
           class="page-link <?= $i == $paginationData['current_page'] ? 'active' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>
    
    <?php if ($paginationData['current_page'] < $paginationData['total_pages']): ?>
        <a href="?page=<?= $paginationData['current_page'] + 1 ?><?= isset($_GET['search_query']) ? '&search_query=' . urlencode($_GET['search_query']) . '&search_type=' . urlencode($_GET['search_type'] ?? 'both') : '' ?>" 
           class="page-link next">
            Вперед →
        </a>
    <?php endif; ?>
</div>
<?php endif; ?>
