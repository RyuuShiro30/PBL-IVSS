let currentSlide = 0;

function slideGallery(direction) {
    const container = document.getElementById("galleryContainer");
    const itemWidth = container.children[0].offsetWidth; 
    const totalItems = container.children.length;

    const maxSlide = Math.ceil(totalItems / 3) - 1;

    currentSlide += direction;

    if (currentSlide < 0) currentSlide = 0;
    if (currentSlide > maxSlide) currentSlide = maxSlide;

    const offset = -(currentSlide * itemWidth * 3);
    container.style.transform = `translateX(${offset}px)`;
}
