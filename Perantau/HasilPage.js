var titleEl = document.getElementById('hasil-title');
var descEl = document.getElementById('hasil-desc');
var imgEl = document.getElementById('hasil-img');

var result = localStorage.getItem('interiology-result');

if (result == 'scandinavian') {
    titleEl.textContent = "Si Hangat Scandinavian";
    descEl.textContent = "Kamu menyukai ruangan yang hangat dan nyaman dengan sentuhan natural...";
    imgEl.src = "Image/img-Scandinavian.png";
} 
else if (result == 'modern') {
    titleEl.textContent = "Si Elegan Modern";
    descEl.textContent = "Kamu suka ruangan yang terlihat mewah dan berkelas...";
    imgEl.src = "Image/img-Modern.png";
} 
else if (result == 'bohemian') {
    titleEl.textContent = "Si Ceria Bohemian";
    descEl.textContent = "Kamu suka ruangan yang penuh warna dan karakter!...";
    imgEl.src = "Image/img-Bohemian.png";
} 
else {
    titleEl.textContent = "Si Kalem Minimalis";
    descEl.textContent = "Kamu cocok terhadap furnitur yang berbahan kayu dan berbentuk simple...";
    imgEl.src = "Image/img-Minimalis.png";
}
