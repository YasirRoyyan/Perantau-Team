var titleEl = document.getElementById('hasil-title');
var descEl = document.getElementById('hasil-desc');
var imgEl = document.getElementById('hasil-img');
var downloadLink = document.getElementById('download-link');

var result = localStorage.getItem('interiology-result');

if (result == 'scandinavian') {
    titleEl.textContent = "Si Hangat Scandinavian";
    descEl.textContent = "Kamu menyukai ruangan yang hangat dan nyaman dengan sentuhan natural, warna lembut, dan furnitur kayu yang membuat ruangan terasa akrab.";
    imgEl.src = "Image/img-Scandinavian.png";
} 
else if (result == 'modern') {
    titleEl.textContent = "Si Elegan Modern";
    descEl.textContent = "Kamu suka ruangan yang terlihat mewah dan berkelas, dengan bentuk tegas, warna netral, dan detail yang bersih tanpa terlalu ramai.";
    imgEl.src = "Image/img-Modern.png";
} 
else if (result == 'bohemian') {
    titleEl.textContent = "Si Ceria Bohemian";
    descEl.textContent = "Kamu suka ruangan yang penuh warna dan karakter, dengan dekorasi ekspresif, tekstur berlapis, dan suasana yang bebas serta kreatif.";
    imgEl.src = "Image/img-Bohemian.png";
} 
else {
    titleEl.textContent = "Si Kalem Minimalis";
    descEl.textContent = "Kamu cocok terhadap furnitur yang berbahan kayu dan berbentuk simple, dengan warna yang kalem seperti coklat, putih, dan krem. Coba merek seperti Fabelio, Scandinavian, Warjo dsb.";
    imgEl.src = "Image/img-container.png";
}

if (downloadLink && imgEl) {
    downloadLink.href = imgEl.src;
    downloadLink.setAttribute('download', 'interiology-hasil.png');
}
