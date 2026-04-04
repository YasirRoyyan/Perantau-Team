var questions = [
    {
        question: "Kamu lebih suka ruangan yang bagaimana?",
        options: ["Simpel dan rapi", "Hangat dan nyaman", "Modern dan elegan", "Berwarna dan ceria"]
    },
    {
        question: "Warna apa yang paling kamu suka untuk ruangan?",
        options: ["Putih dan krem", "Coklat dan kayu", "Abu-abu dan hitam", "Biru dan hijau"]
    },
    {
        question: "Furniture seperti apa yang kamu suka?",
        options: ["Kayu natural", "Sofa empuk besar", "Metal dan kaca", "Campuran warna-warni"]
    },
    {
        question: "Bagaimana pencahayaan ideal menurutmu?",
        options: ["Cahaya alami dari jendela", "Lampu hangat redup", "Lampu terang modern", "Lampu warna-warni"]
    },
    {
        question: "Dekorasi apa yang kamu suka di dinding?",
        options: ["Minimalis tanpa banyak hiasan", "Foto keluarga dan kenangan", "Lukisan abstrak", "Poster dan seni pop"]
    },
    {
        question: "Lantai seperti apa yang kamu pilih?",
        options: ["Kayu natural", "Karpet tebal", "Marmer atau granit", "Vinyl berwarna"]
    },
    {
        question: "Bagaimana suasana ruangan impianmu?",
        options: ["Tenang dan damai", "Hangat seperti rumah nenek", "Mewah dan berkelas", "Seru dan penuh energi"]
    },
    {
        question: "Apa yang paling penting dalam sebuah ruangan?",
        options: ["Kerapian", "Kenyamanan", "Keindahan", "Keunikan"]
    },
    {
        question: "Apakah kamu menyukai tanaman di dalam ruangan?",
        options: ["Ya, sedikit saja", "Ya, banyak tanaman", "Tidak terlalu", "Lebih suka bunga palsu"]
    },
    {
        question: "Gaya interior mana yang paling menarik?",
        options: ["Minimalis", "Scandinavian", "Industrial", "Bohemian"]
    }
];

var images = [
    "https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800",
    "https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800",
    "https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?w=800",
    "https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=800",
    "https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800",
    "https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800",
    "https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?w=800",
    "https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=800",
    "https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800",
    "https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800"
];

var titles = [
    "Mulailah menjawab pertanyaan...",
    "Mulailah menjawab pertanyaan...",
    "Mulailah menjawab pertanyaan...",
    "Terus jawab pertanyaannya...",
    "Terus jawab pertanyaannya...",
    "Terus jawab pertanyaannya...",
    "Sedikit lagi...",
    "Sedikit lagi...",
    "Ruanganmu hampir selesai...",
    "Ruanganmu hampir selesai..."
];

var currentQuestion = 0;
var answers = [];

function showQuestion() {
    var titleEl = document.getElementById('asesmen-title');
    var imgEl = document.getElementById('asesmen-img');
    var progressEl = document.getElementById('progress');
    var questionEl = document.getElementById('question');
    var optionsEl = document.getElementById('options');

    var q = questions[currentQuestion];

    titleEl.textContent = titles[currentQuestion];
    imgEl.src = images[currentQuestion];
    progressEl.textContent = (currentQuestion + 1) + " dari " + questions.length;
    questionEl.textContent = q.question;

    optionsEl.innerHTML = '';

    for (var i = 0; i < q.options.length; i++) {
        var btn = document.createElement('button');
        btn.className = 'option-btn';
        btn.innerHTML = '<span class="option-number">' + (i + 1) + '</span><span>' + q.options[i] + '</span>';
        btn.setAttribute('data-index', i);
        btn.onclick = function() {
            var selectedIndex = parseInt(this.getAttribute('data-index'));

            var allBtns = document.querySelectorAll('.option-btn');
            for (var k = 0; k < allBtns.length; k++) {
                allBtns[k].disabled = true;
                allBtns[k].style.pointerEvents = 'none';
            }

            answers.push(selectedIndex);
            this.classList.add('selected');

            setTimeout(function() {
                currentQuestion++;
                if (currentQuestion < questions.length) {
                    showQuestion();
                } else {
                    calculateResult();
                }
            }, 500);
        };
        optionsEl.appendChild(btn);
    }
}

function calculateResult() {
    var counts = [0, 0, 0, 0];
    for (var i = 0; i < answers.length; i++) {
        counts[answers[i]]++;
    }

    var maxIndex = 0;
    for (var j = 1; j < counts.length; j++) {
        if (counts[j] > counts[maxIndex]) {
            maxIndex = j;
        }
    }

    var types = ["minimalis", "scandinavian", "modern", "bohemian"];
    var result = types[maxIndex];

    localStorage.setItem('interiology-result', result);
    window.location.href = 'HasilPage.html';
}

window.onload = function() {
    showQuestion();
};