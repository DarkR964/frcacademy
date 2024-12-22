<?php
session_start();

// Kullanıcı oturum kontrolü
if (!isset($_SESSION['user_id'])) {
    header("Location: ../php/login.php");
    exit();
}

// Veritabanı bağlantısı
require '../includes/dbconnection.php';

// Kullanıcı adını getirme
$user_id = $_SESSION['user_id'];
$query = "SELECT name FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['name'];
} else {
    $name = 'Bilinmeyen Kullanıcı';
}

// Video ve açıklama varsayılan değerleri
$video_src = '../video/Mekanik/el_aletleri_kullanımı1.mov';
$video_title = "Mekanik 1. Bölüm - El Aletleri";
$video_description = "Bu Videoda El Aletlerini Anlatık";
$detailed_description = "Bu videoda, çeşitli el aletlerinin kullanımı anlatılmaktadır. Pense, metal malzemeleri tutmak, kesmek ve şekil vermek için kullanılırken, yan keski ince malzemeleri kesmek için kullanılır. Yıldız tornavida ise vidaları sökmek için kullanılır. Çekiç, bir şeyleri yamultmak için, tokmak ise buna benzer bir işlevi vardır. İngiliz anahtarı somunları sıkmak veya gevşetmek için kullanılır. Ayrıca, alyan anahtarı, kumpas, su terazisi, perçin tabancası ve matkap gibi el aletleri de tanıtılmakta ve kullanım amaçları açıklanmaktadır. Son olarak, taşlama aleti ve matkap uçları ile ilgili bilgi verilmektedir.";


// GET parametresi ile video seçimi
if (isset($_GET['video'])) {
    $video_id = htmlspecialchars($_GET['video']);
    switch ($video_id) {
        case 'mekanik1':
            $video_src = '../video/Mekanik/el_aletleri_kullanımı1.mov';
            $video_title = "Mekanik 1. Bölüm - El Aletleri";
            $video_description = "Bu Videoda El Aletlerini Anlatık.";
            $detailed_description = "Bu videoda, çeşitli el aletlerinin kullanımı anlatılmaktadır. Pense, metal malzemeleri tutmak, kesmek ve şekil vermek için kullanılırken, yan keski ince malzemeleri kesmek için kullanılır. Yıldız tornavida ise vidaları sökmek için kullanılır. Çekiç, bir şeyleri yamultmak için, tokmak ise buna benzer bir işlevi vardır. İngiliz anahtarı somunları sıkmak veya gevşetmek için kullanılır. Ayrıca, alyan anahtarı, kumpas, su terazisi, perçin tabancası ve matkap gibi el aletleri de tanıtılmakta ve kullanım amaçları açıklanmaktadır. Son olarak, taşlama aleti ve matkap uçları ile ilgili bilgi verilmektedir.";
            break;
        case 'mekanik2':
            $video_src = '../video/Mekanik/motorlu_el_aletleri_2.mov';
            $video_title = "Mekanik 2. Bölüm - Motorlu El Aletleri 2 ";
            $video_description = "Bu Videoda Motorlu El Aletlerini Anlatık";
            $detailed_description = "Bu videoda motorlu el aletlerinin kullanımını öğreniyoruz. İlk olarak profil delme işlemiyle başlıyoruz. Profili mengene ile sıkıştırdıktan sonra matkabı alıp 90° dik bir açıyla delmeye başlıyorum. Tork ayarımın düşük olduğunu görüp, bu yüzden zorlandığımı fark ediyorum. Bu durumda tork ayarını doğru yapmak çok önemli. Ardından perçin tabancasıyla perçin atmayı gösteriyorum. Deldiğim yere metrik 5 bir perçin yerleştirip, tabancanın namlusuna perçin takıp sıkmaya başlıyorum. Perçin kırıldığında doğru işlemi yaptığımı anlıyorum. Sonraki aşama ise avuç içi taşlama kullanımı. Bu işlemde mengeneyi iyice sıkıştırmak ve güvenlik önlemleri almak çok önemli. Gözlük ve eldiven takarak güvenli bir şekilde işlem yapıyorum. Taşlama işlemi sırasında dikkat etmem gereken bir şey var, o da disklerin mengeneye temas etmemesi. Aksi takdirde kıvılcım çıkabiliyor. Bu nedenle taşlama yaparken güvenlik önlemlerini almayı unutmazsınız. Son olarak sütun matkabının nasıl çalıştığını gösteriyorum. Sütun matkabının tekerleğini döndürebilmek için kilidi açıyorum ve malzemeyi mengene ile sabitliyorum. Sütun matkabının lazerini açarak, hangi noktayı delmem gerektiğini işaretliyorum. Tork ayarını yapıp, uygun tork ile deliği rahatça açabiliyorum. İlk başta zorlandığımda torku tekrar ayarlayıp, düzgün bir şekilde deliyorum. Bu video motorlu el aletlerinin güvenli ve verimli kullanımına dair önemli ipuçları veriyor.";

        default:
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yazılım Eğitimi</title>
    <link rel="stylesheet" href="../css/eğitim.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <span class="welcome-message"><strong><?php echo htmlspecialchars($name); ?></strong></span>
            <ul>
            <li><a href="../php/main.php">Ana Sayfa</a></li>
            </ul>
        </div>
    </nav>

    <!-- İçerik -->
    <div class="container">
        <!-- Video ve Materyaller Bölümü -->
        <div class="lesson-details">
            <div class="lesson-block">
                <!-- Video -->
                <div class="video-container">
                    <video controls controlsList="nodownload" oncontextmenu="return false;">
                        <source src="<?php echo htmlspecialchars($video_src); ?>" type="video/mp4">
                        Tarayıcınız video etiketini desteklemiyor.
                    </video>
                </div>
                <!-- Video Metni ve Materyaller -->
                <div class="lesson-content">
                    <h2><?php echo htmlspecialchars($video_title); ?></h2>
                    <p><?php echo htmlspecialchars($video_description); ?></p>
                    
                    <button class="collapsible">Detaylı Açıklama</button>
                    <div class="content">
                        <p><?php echo htmlspecialchars($detailed_description); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dersler Bölümü Sağda -->
        <div class="lessons">
            <h2>Dersler</h2>
            <ul>
                <li><a href="?video=mekanik1">Video 1: El Aletleri </a></li>
                <li><a href="?video=mekanik2">Video 2: Motorlu El Aletleri 2 </a></li>
            </ul>
        </div>
    </div>

    <script>
        // Açılır/Kapanır açıklama
        const coll = document.querySelectorAll(".collapsible");
        coll.forEach(button => {
            button.addEventListener("click", function () {
                this.classList.toggle("active");
                const content = this.nextElementSibling;
                if (content.style.display === "block") {
                    content.style.display = "none";
                } else {
                    content.style.display = "block";
                }
            });
        });
    </script>
</body>
</html>
