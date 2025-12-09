document.addEventListener("DOMContentLoaded", function () {
    const cards = document.querySelectorAll(".hidden-card");
    const loadMoreBtn = document.getElementById("loadMoreBtn");

    let visibleCards = 6; // jumlah awal
    showCards(visibleCards);

    function showCards(count) {
        cards.forEach((card, index) => {
            if (index < count) card.style.display = "flex";
        });

        // sembunyikan tombol kalau semua berita sudah tampil
        if (visibleCards >= cards.length) {
            loadMoreBtn.style.display = "none";
        }
    }

    loadMoreBtn.addEventListener("click", function () {
        visibleCards += 6; // tampilkan 6 setiap klik
        showCards(visibleCards);
    });
});
