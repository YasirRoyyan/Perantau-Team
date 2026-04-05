var titleEl = document.getElementById('hasil-title');
var descEl = document.getElementById('hasil-desc');
var imgEl = document.getElementById('hasil-img');

var result = localStorage.getItem('interiology-result');

if (result == 'scandinavian') {
    titleEl.textContent = "Si Hangat Scandinavian";
    descEl.textContent = "Kamu menyukai ruangan yang hangat dan nyaman dengan sentuhan natural...";
    imgEl.src = "https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800";
} 
else if (result == 'modern') {
    titleEl.textContent = "Si Elegan Modern";
    descEl.textContent = "Kamu suka ruangan yang terlihat mewah dan berkelas...";
    imgEl.src = "https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?w=800";
} 
else if (result == 'bohemian') {
    titleEl.textContent = "Si Ceria Bohemian";
    descEl.textContent = "Kamu suka ruangan yang penuh warna dan karakter!...";
    imgEl.src = "https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=800";
} 
else {
    titleEl.textContent = "Si Kalem Minimalis";
    descEl.textContent = "Kamu cocok terhadap furnitur yang berbahan kayu dan berbentuk simple...";
    imgEl.src = "https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800";
}