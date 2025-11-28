    document.addEventListener('DOMContentLoaded', function() {
        const loadMoreBtn = document.getElementById('loadMoreBtn');
        const hiddenCards = document.querySelectorAll('.hidden-card');

        loadMoreBtn.addEventListener('click', function() {
            // Menampilkan semua kartu yang memiliki class 'hidden-card'
            hiddenCards.forEach(card => {
                card.classList.remove('hidden-card');
            });
            
            // Menyembunyikan tombol "Load More"
            loadMoreBtn.style.display = 'none';
        });
    });