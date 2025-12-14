// Просмотр оригинала в модальном окне
document.addEventListener('DOMContentLoaded', function() {
    const viewOriginalButtons = document.querySelectorAll('.view-original');
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const closeModal = document.getElementById('closeModal');
    const imageName = document.getElementById('imageName');
    const imageSize = document.getElementById('imageSize');
    
    // Открытие модального окна
    viewOriginalButtons.forEach(button => {
        button.addEventListener('click', function() {
            const originalSrc = this.getAttribute('data-src');
            const photoCard = this.closest('.photo-card');
            const name = photoCard.querySelector('.photo-name').textContent;
            const size = photoCard.querySelector('.photo-size').textContent;
            
            modalImage.src = originalSrc;
            modalImage.alt = name;
            imageName.textContent = name;
            imageSize.textContent = size;
            
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });
    
    // Закрытие модального окна
    closeModal.addEventListener('click', function() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    });
    
    // Закрытие по клику вне изображения
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
    
    // Закрытие по Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
});