/* Marshah Holding — Data projek portfolio.
   Global var PORTFOLIO: array of project objects.
   Digunakan oleh portfolio.html, rumah-siap.html dan portfolio-detail.html.
   Kategori sah: banglo-setingkat, banglo-2tingkat, moden, tradisional. */
var PORTFOLIO = [
  {
    id: "banglo-aisyah",
    name: "Banglo Aisyah",
    location: "Kota Bharu, Kelantan",
    category: "banglo-setingkat",
    area: 2200,
    bedrooms: 4,
    bathrooms: 3,
    cost: "RM 365,000",
    status: "Siap",
    description: [
      "Banglo Aisyah ialah rumah banglo setingkat moden dengan reka bentuk terbuka yang mesra keluarga. Susun atur ruang tamu dan ruang makan yang lapang memberikan keselesaan maksimum untuk keluarga besar.",
      "Dibina di atas tanah sendiri di Kota Bharu, projek ini disiapkan mengikut jadual dengan kemasan berkualiti tinggi serta pengudaraan semula jadi yang baik di setiap bilik."
    ],
    images: [
      "assets/img/rumah-01.jpg",
      "assets/img/portfolio-01.jpg",
      "assets/img/rumah-02.jpg",
      "assets/img/portfolio-05.jpg"
    ]
  },
  {
    id: "banglo-damia",
    name: "Banglo Damia",
    location: "Pasir Mas, Kelantan",
    category: "banglo-2tingkat",
    area: 2600,
    bedrooms: 4,
    bathrooms: 3,
    cost: "RM 398,000",
    status: "Dalam Pembinaan",
    description: [
      "Banglo Damia merupakan rumah banglo dua tingkat kontemporari dengan fasad kemas dan garisan yang tegas. Ruang atas dikhususkan untuk bilik tidur utama berserta ruang keluarga peribadi.",
      "Kini dalam pembinaan di Pasir Mas, projek ini menampilkan siling tinggi di ruang tamu serta tingkap besar yang memaksimumkan cahaya semula jadi sepanjang hari."
    ],
    images: [
      "assets/img/rumah-04.jpg",
      "assets/img/portfolio-03.jpg",
      "assets/img/portfolio-06.jpg",
      "assets/img/rumah-05.jpg"
    ]
  },
  {
    id: "banglo-iman",
    name: "Banglo Iman",
    location: "Tumpat, Kelantan",
    category: "moden",
    area: 2200,
    bedrooms: 4,
    bathrooms: 3,
    cost: "RM 342,000",
    status: "Siap",
    description: [
      "Banglo Iman ialah rumah banglo bergaya moden kontemporari yang menekankan kesederhanaan dan fungsi. Reka bentuk fasad menggabungkan warna neutral dengan sentuhan kayu yang hangat.",
      "Terletak di Tumpat, rumah ini telah siap sepenuhnya dengan dapur kering dan basah yang berasingan serta ruang letak kereta bertutup untuk dua buah kenderaan."
    ],
    images: [
      "assets/img/rumah-06.jpg",
      "assets/img/portfolio-08.jpg",
      "assets/img/rumah-07.jpg",
      "assets/img/portfolio-02.jpg"
    ]
  },
  {
    id: "banglo-zahra",
    name: "Banglo Zahra",
    location: "Bachok, Kelantan",
    category: "tradisional",
    area: 2100,
    bedrooms: 4,
    bathrooms: 3,
    cost: "RM 355,000",
    status: "Siap",
    description: [
      "Banglo Zahra mengangkat elemen seni bina tradisional Kelantan dengan bumbung tinggi berlapis dan serambi luas yang sesuai untuk iklim tempatan. Ia menggabungkan warisan dengan keselesaan moden.",
      "Dibina di Bachok berhampiran pantai, rumah ini menawarkan pengudaraan silang yang cemerlang serta halaman lapang untuk aktiviti keluarga."
    ],
    images: [
      "assets/img/rumah-08.jpg",
      "assets/img/portfolio-04.jpg",
      "assets/img/rumah-09.jpg",
      "assets/img/portfolio-07.jpg"
    ]
  },
  {
    id: "banglo-hana",
    name: "Banglo Hana",
    location: "Machang, Kelantan",
    category: "moden",
    area: 2650,
    bedrooms: 4,
    bathrooms: 3,
    cost: "RM 380,000",
    status: "Dalam Pembinaan",
    description: [
      "Banglo Hana ialah banglo moden minimalis dengan fasad putih bersih dan tingkap panel besar. Konsep ruang terbuka menyatukan ruang tamu, ruang makan dan dapur dalam satu aliran yang lancar.",
      "Sedang dibina di Machang, projek ini turut dilengkapi ruang kajian serta bilik tidur tetamu di tingkat bawah untuk kemudahan warga tua."
    ],
    images: [
      "assets/img/rumah-10.jpg",
      "assets/img/portfolio-05.jpg",
      "assets/img/rumah-11.jpg",
      "assets/img/portfolio-01.jpg"
    ]
  },
  {
    id: "banglo-sofia",
    name: "Banglo Sofia",
    location: "Gua Musang, Kelantan",
    category: "banglo-2tingkat",
    area: 3500,
    bedrooms: 5,
    bathrooms: 4,
    cost: "RM 372,000",
    status: "Siap",
    description: [
      "Banglo Sofia ialah banglo dua tingkat premium yang direka untuk keluarga besar. Tingkat bawah menempatkan ruang tamu formal dan tidak formal, manakala tingkat atas mempunyai lima bilik tidur yang luas.",
      "Siap dibina di Gua Musang, rumah ini menampilkan tangga berpusat sebagai tumpuan reka bentuk serta balkoni utama yang menghadap pemandangan sekitar."
    ],
    images: [
      "assets/img/rumah-12.jpg",
      "assets/img/portfolio-09.jpg",
      "assets/img/portfolio-06.jpg",
      "assets/img/rumah-03.jpg"
    ]
  },
  {
    id: "banglo-moden-4bilik",
    name: "Banglo Moden 4 Bilik",
    location: "Kota Bharu, Kelantan",
    category: "moden",
    area: 2400,
    bedrooms: 4,
    bathrooms: 3,
    cost: "RM 370,000",
    status: "Siap",
    description: [
      "Banglo Moden 4 Bilik menawarkan reka bentuk kontemporari yang praktikal dengan susun atur yang mengutamakan privasi setiap ahli keluarga. Ruang tamu berkonsep dua aras memberikan dimensi menarik.",
      "Projek siap di Kota Bharu ini dilengkapi kemasan lantai berkualiti, dapur moden serta sistem pengudaraan yang cekap untuk keselesaan sepanjang tahun."
    ],
    images: [
      "assets/img/portfolio-01.jpg",
      "assets/img/rumah-02.jpg",
      "assets/img/portfolio-10.jpg",
      "assets/img/rumah-01.jpg"
    ]
  },
  {
    id: "banglo-setingkat-kontemporari",
    name: "Banglo Setingkat Kontemporari",
    location: "Pasir Mas, Kelantan",
    category: "banglo-setingkat",
    area: 1850,
    bedrooms: 3,
    bathrooms: 2,
    cost: "RM 285,000",
    status: "Siap",
    description: [
      "Banglo Setingkat Kontemporari sesuai untuk keluarga muda yang mahukan rumah kemas dan mampu milik tanpa mengorbankan gaya. Reka bentuk satu aras memudahkan pergerakan dan penyelenggaraan.",
      "Dibina di Pasir Mas, rumah ini menampilkan fasad bersih dengan kombinasi konkrit terdedah dan panel kayu yang memberikan sentuhan moden."
    ],
    images: [
      "assets/img/portfolio-02.jpg",
      "assets/img/rumah-07.jpg",
      "assets/img/portfolio-05.jpg",
      "assets/img/rumah-06.jpg"
    ]
  },
  {
    id: "banglo-tradisional-kelantan",
    name: "Banglo Tradisional Kelantan",
    location: "Bachok, Kelantan",
    category: "tradisional",
    area: 2100,
    bedrooms: 4,
    bathrooms: 3,
    cost: "RM 340,000",
    status: "Siap",
    description: [
      "Banglo Tradisional Kelantan menghidupkan semula warisan seni bina rumah Melayu Kelantan dengan bumbung limas, ukiran halus dan serambi lapang yang menjadi ciri khas rumah kampung.",
      "Projek di Bachok ini memadankan bahan binaan moden dengan estetika tradisional supaya kekal tahan lama sambil mengekalkan roh dan identiti tempatan."
    ],
    images: [
      "assets/img/portfolio-04.jpg",
      "assets/img/rumah-08.jpg",
      "assets/img/portfolio-07.jpg",
      "assets/img/rumah-09.jpg"
    ]
  },
  {
    id: "banglo-2tingkat-premium",
    name: "Banglo 2 Tingkat Premium",
    location: "Gua Musang, Kelantan",
    category: "banglo-2tingkat",
    area: 3200,
    bedrooms: 5,
    bathrooms: 4,
    cost: "RM 450,000",
    status: "Siap",
    description: [
      "Banglo 2 Tingkat Premium ialah kediaman mewah dengan ruang lapang di setiap sudut. Reka bentuk mementingkan pencahayaan semula jadi melalui tingkap ketinggian penuh dan ruang legar berkembar.",
      "Siap dibina di Gua Musang, rumah ini dilengkapi bilik tidur utama bersaiz besar dengan bilik air dan almari pakaian bersepadu serta balkoni peribadi."
    ],
    images: [
      "assets/img/portfolio-09.jpg",
      "assets/img/rumah-12.jpg",
      "assets/img/portfolio-03.jpg",
      "assets/img/rumah-05.jpg"
    ]
  }
];

/* Bantu carian mengikut id */
var PORTFOLIO_BY_ID = {};
for (var i = 0; i < PORTFOLIO.length; i++) {
  PORTFOLIO_BY_ID[PORTFOLIO[i].id] = PORTFOLIO[i];
}
