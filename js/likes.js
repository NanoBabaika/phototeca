console.log("Likes script loaded");
 


const likeBtn = document.getElementById('like_btn');
const likesCount = document.getElementById('likes-count');

if (likeBtn && likesCount) {
    likeBtn.addEventListener('click', function() {
        const photoId = this.dataset.photoId;
        
        if (!photoId) {
            console.error('Photo ID not found');
            return;
        }
        
        // Визуальная обратная связь - добавляем класс на время запроса
        this.classList.add('loading');
        
        sendLikeRequest(photoId);
    });
}

function sendLikeRequest(photoId) {
    fetch('./api/like.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ photo_id: photoId })
    })
    .then(response => response.json())
    .then(data => {
        // Убираем класс loading
        const btn = document.getElementById('like_btn');
        if (btn) btn.classList.remove('loading');
        
        if (data.status === 'success') {
            // Обновляем счетчик
            if (likesCount) {
                likesCount.textContent = data.newLikeCount;
            }
            
            // Меняем состояние кнопки
            if (btn) {
                if (data.action === 'liked') {
                    btn.classList.add('liked');
                } else {
                    btn.classList.remove('liked');
                }
            }
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Request failed:', error);
        alert('Network error');
        
        const btn = document.getElementById('like_btn');
        if (btn) btn.classList.remove('loading');
    });
}
 

// Найдем все кнопки через цикл и повесим на них обработчик событий
const buttons = document.querySelectorAll('.like-btn');
 
// Обработка кнопок в галерее
buttons.forEach(button => {
    button.addEventListener('click', (event) => {
         
        const clickedButton = event.currentTarget;
        const photoId = clickedButton.dataset.photoId;
        console.log('Нажата кнопка like!', photoId);

        const likesCountElement = clickedButton.querySelector('.likes-count');
        
 
        const current = parseInt(likesCountElement.textContent) || 0;

     
        // Интерфейс и сервер должны работать параллельно

        if (likedIds.includes(photoId)) {
            //  ID уже есть в массиве -> Убираем лайк
            clickedButton.classList.remove('liked');
            likesCountElement.textContent = current - 1;
            
            //  Удаляем ID из массива 
            const index = likedIds.indexOf(photoId);
            if (index > -1) {
                likedIds.splice(index, 1); 
            }
            
        } else {
            // ID еще нет в массиве ! Ставим лайк
            clickedButton.classList.add('liked');
            likesCountElement.textContent = current + 1;

            // Добавляем ID в массив
            likedIds.push(photoId);
        }

        sendLikeRequest(photoId);
    
        
    });
});

 