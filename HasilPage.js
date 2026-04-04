var resultData = {
    minimalis: {
        title: "Si Kalem Minimalis",
        desc: "Kamu cocok terhadap furnitur yang berbahan kayu dan berbentuk simple, dengan warna yang kalem seperti coklat, putih, dan krem. Coba merek seperti Fabello, Scandinavian, Warjo dsb.",
        image: "https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800"
    },
    scandinavian: {
        title: "Si Hangat Scandinavian",
        desc: "Kamu menyukai ruangan yang hangat dan nyaman dengan sentuhan natural. Furnitur kayu terang, karpet lembut, dan warna earthy tone sangat cocok untukmu. Coba merek seperti IKEA, Informa, dsb.",
        image: "https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800"
    },
    modern: {
        title: "Si Elegan Modern",
        desc: "Kamu suka ruangan yang terlihat mewah dan berkelas. Furnitur dengan material metal, kaca, dan warna monokrom adalah pilihanmu. Coba merek seperti Vivere, Cellini, dsb.",
        image: "https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?w=800"
    },
    bohemian: {
        title: "Si Ceria Bohemian",
        desc: "Kamu suka ruangan yang penuh warna dan karakter! Campuran pattern, tanaman, dan dekorasi unik adalah gayamu. Coba merek seperti Urban Outfitters, Anthropologie, dsb.",
        image: "https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=800"
    }
};

var titleEl = document.getElementById('hasil-title');
var descEl = document.getElementById('hasil-desc');
var imgEl = document.getElementById('hasil-img');
var downloadLink = document.getElementById('download-link');

document.addEventListener('DOMContentLoaded', function() {
    var result = localStorage.getItem('interiology-result');
    if (!result || !resultData[result]) {
        result = 'minimalis';
    }

    var data = resultData[result];

    titleEl.textContent = data.title;
    descEl.textContent = data.desc;
    imgEl.src = data.image;

    downloadLink.addEventListener('click', function(e) {
        e.preventDefault();
        alert('Fitur download gambar akan segera tersedia!');
    });

    var section = document.querySelector('.hasil-section');
    section.style.opacity = '0';
    section.style.transform = 'translateY(20px)';
    section.style.transition = 'opacity 0.6s, transform 0.6s';

    setTimeout(function() {
        section.style.opacity = '1';
        section.style.transform = 'translateY(0)';
    }, 200);
});