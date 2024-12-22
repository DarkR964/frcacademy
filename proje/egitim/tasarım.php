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
$video_src = '../video/Tasarım/ders1.mp4';
$video_title = "Tasarım 1. Bölüm - İki Boyutlu Çizim";
$video_description = "Bu derste İki boyutlu çizimlerin ve temel komutları anlatık";
$detailed_description = "Bu derste, Fusion 360 programında 2 boyutlu çizimler yapma süreci anlatılmaktadır. İlk olarak, Create Sketch komutuyla çizim yapılmaya başlanır. Kullanıcılar, daire, çizgi ve dikdörtgen gibi geometrik şekilleri çizebilir ve bunları boyutlandırabilir. Farklı komutlarla eğik çizgiler, dikdörtgenler ve yamuk şekiller çizilebilir. Ayrıca, çizgiler arasındaki mesafeler ölçülerek doğruluk sağlanır.
Mirror komutu, çizimlerin simetrik olarak yansıtılmasını sağlar. Farklı araçlar kullanılarak çizimlerin üzerine yazı eklenebilir, köşeler yuvarlatılabilir, çizgiler silinebilir ve boyutlandırılabilir. Kamerayı döndürme ve yakınlaştırma seçenekleriyle çizim alanı daha rahat kontrol edilebilir. 
Shift tuşu ile birden fazla öğe seçilip hizalanabilir veya aynı boyutlara getirilerek çizimler düzenlenebilir. Bu araçlarla çizimler daha hassas ve fonksiyonel hale getirilir.
";


// GET parametresi ile video seçimi
if (isset($_GET['video'])) {
    $video_id = htmlspecialchars($_GET['video']);
    switch ($video_id) {
        case 'tasarım1':
            $video_src = '../video/Tasarım/ders1.mp4';
            $video_title = "Tasarım 1. Bölüm -  İki Boyutlu Çizim";
            $video_description = "Bu derste İki boyutlu çizimlerin ve temel komutları anlatık";
            $detailed_description = "Bu derste, Fusion 360 programında 2 boyutlu çizimler yapma süreci anlatılmaktadır. İlk olarak, Create Sketch komutuyla çizim yapılmaya başlanır. Kullanıcılar, daire, çizgi ve dikdörtgen gibi geometrik şekilleri çizebilir ve bunları boyutlandırabilir. Farklı komutlarla eğik çizgiler, dikdörtgenler ve yamuk şekiller çizilebilir. Ayrıca, çizgiler arasındaki mesafeler ölçülerek doğruluk sağlanır.
Mirror komutu, çizimlerin simetrik olarak yansıtılmasını sağlar. Farklı araçlar kullanılarak çizimlerin üzerine yazı eklenebilir, köşeler yuvarlatılabilir, çizgiler silinebilir ve boyutlandırılabilir. Kamerayı döndürme ve yakınlaştırma seçenekleriyle çizim alanı daha rahat kontrol edilebilir. 
Shift tuşu ile birden fazla öğe seçilip hizalanabilir veya aynı boyutlara getirilerek çizimler düzenlenebilir. Bu araçlarla çizimler daha hassas ve fonksiyonel hale getirilir.
";
            break;
        case 'tasarım2':
            $video_src = '../video/Tasarım/ders2.mp4';
            $video_title = "Tasarım 2. Bölüm - 2 Boyuttan 3 Boyuta";
            $video_description = "2 Boyutlu cisimlerin 3 boyutlandırılması ve 3 boyut komutlarını nasıl kullanıldığı ve nasıl kullanılması gerektiği anlatık";
            $detailed_description = "Fusion 360 ile 2 Boyuttan 3 Boyuta Geçiş Dersi

Merhaba arkadaşlar, bugünki dersimizde önceki derste yaptığımız 2 boyutlu çizimleri nasıl 3 boyutlu hale getireceğimizi göstereceğim.
Sketch Bitirme ve Kamera Açıları
Sketch'i bitirmek için Finish Sketch komutunu kullanıyoruz. Bu komutu sağ üstte ya da Sketch arayüzünde bulabilirsiniz. Finish Sketch'e bastığınızda çiziminiz tamamlanır.
Kamera açısını değiştirmek için önce Shift tuşuna, sonra fare tekerleğine basıp farenizi hareket ettirin. Bu sayede 360 derece perspektifinizi değiştirebilirsiniz.
2 Boyutlu Çizimleri 3 Boyutlu Hale Getirme
Extrude Komutu: 2 boyutlu bir yüzeyi 3 boyutlu hale getirmek için kullanılır.
Önce çiziminizin içini seçin.
Ardından E kısayoluna basarak veya Create menüsünden Extrude seçeneğini tıklayarak çiziminizi yukarı ya da aşağı hareket ettirebilirsiniz.
Bu şekilde yeni bir parça oluşturabilir veya mevcut bir parçaya ekleme yapabilirsiniz.
Yüzey Üzerinde Yeni Çizimler
3 boyutlu bir nesnenin herhangi bir yüzeyini seçip C kısayoluyla yeni bir çizim yapabilirsiniz.
Çizdiğiniz yeni şekli tekrar E komutuyla 3 boyutlu hale getirebilirsiniz.
Extrude ve Press Komutlarının Farklı Kullanımları
Extrude komutunu kullanarak:
Şekli yukarı doğru uzatarak yeni bir parça ekleyebilir veya mevcut parçayla birleştirebilirsiniz.
Aşağı doğru çekerek parçaya delik açabilirsiniz.
Press komutuyla:
Bir nesnenin yüzeyine baskı uygulayarak şekli inceltebilir veya kalınlaştırabilirsiniz.
Köşe Yuvarlama (“Fillet” Komutu)
Fillet komutunu kullanarak nesnelerin köşelerini yuvarlatabilirsiniz:
İstediğiniz köşeyi seçip oku aşağı veya yukarı hareket ettirerek yuvarlatma yapabilirsiniz.
Shift tuşuna basılı tutarak birden fazla köşeyi seçip toplu bir şekilde yuvarlatabilirsiniz.
Delik Açma
Hole veya Extrude komutlarını kullanarak delikler oluşturabilirsiniz:
Delik açmak için bir yüzeyi seçin ve E kısayolunu kullanarak çiziminizi aşağı doğru çekin.
Bu delikler, çizimlerde hassasiyet sağlar ve montaj parçaları için gereklidir.
Yeni Parça (“New Body”) ve Bileşen (“New Component”)
New Body: Oluşturduğunuz parçaları soldaki “Bodies” bölümünde görürek düzenleyebilirsiniz.
New Component: Yeni bir bileşen olarak kaydederseniz, parça “Bodies” yerine “Components” bölümüne eklenir.
Parçaları Düzenleme
Soldaki menüyü kullanarak:
Parçanın üstüne iki kere tıklayarak silebilir veya düzenleyebilirsiniz.
Seçtiğiniz parçanın altında mavi bir çizgi görünür, bu sayede hangi parça olduğunu kolayca bulabilirsiniz.
Genel Notlar
Fusion 360 arayüzü size çizimlerinizi ve parçalarınızı daha kolay bulma imkânı sağlar.
Parçalarınızı düzenlemek ve tasarımlarınıza uygun hale getirmek için bu menülerden yararlanabilirsiniz.
Beni dinlediğiniz için teşekkür ederim. Bugünkü dersimiz bu kadar. Gelecek dersimizde yeni tekniklerle devam edeceğiz.
";
            break;
        case 'tasarım3':
            $video_src = '../video/Tasarım/ders3.mp4';
            $video_title = 'Tasarım 3. Bölüm - Swerve Şasenin Temelleri ';
            $video_description = "Bu derste swerve şasenin temellerini anlatık";
            $detailed_description = "Bu ders, dışarıdan alınan bileşenlerin CAD yazılımına entegrasyonu ve swerve şasesinin oluşturulması üzerine odaklanmaktadır. İlk adım olarak, internetten alınan CAD dosyaları yazılıma yüklenir ve ardından tasarıma eklenir. Swerve modülü doğru açılarda yerleştirilir ve robotun dört köşesine yerleştirilmek üzere çoğaltılır. Şase uzunluğu (740 mm) belirlenerek swerve modüllerinin arasındaki mesafe ayarlanır ve modüller, çizgi ve Move komutları kullanılarak doğru pozisyona getirilir. Daha sonra, bağlantı parçaları ve diğer bileşenler extrude komutuyla şekillendirilir ve yüzeylerin hizalanması Point to Point komutuyla yapılır. Son olarak, swerve modülleri birbirine eklenir, hizalanır ve her parça doğru pozisyonda yerleştirilir. Bu adımların sonucunda, dışarıdan alınan parçalar doğru şekilde tasarıma entegre edilir ve swerve şasesinin temel yapısı oluşturulur.";
            break;
            case 'tasarım4':
                $video_src = '../video/Tasarım/ders4.mp4';
                $video_title = 'Tasarım 4. Bölüm - Swerve Şasenin Tamamlanması';
                $video_description = "Bu derste Swerve Şasenin Tasarımını Tamamladık";
                $detailed_description = "Bu video, bir şase tasarımında alt zemin ve destek parçalarının oluşturulmasını anlatmaktadır. Öncelikle, alttaki yüzeylerden biri seçilip çizim başlatılır ve çizgiler orantılı olarak 80 mm gibi belirli bir ölçü ile çizilir. Çizimlerin Swerve modüllerine değmemesi sağlanarak simetrik bir tasarım elde edilir. Daha sonra 2D çizim, Extrude komutuyla 3D'ye dönüştürülür ve parçalar doğru hizalanarak yerleştirilir. Kopyalama ve taşıma işlemleri ile parçalar çoğaltılır. Tasarımda kısayolların kullanımı önemlidir, özellikle Create a Sketch komutunun kısayolu 'C' ve Extrude komutunun kısayolu 'E 'dir Tasarım sürecinde simetrik ölçüler, doğru hizalama ve kısayolların kullanılması gerektiği vurgulanmaktadır.";
                break;
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
    <title>Tasarım Eğitimi</title>
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
                    <video controls>
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
                <li><a href="?video=tasarım1">Video 1: İki Boyutlu Çizim</a></li>
                <li><a href="?video=tasarım2">Video 2: 2 Boyutan 3 Boyuta </a></li>
                <li><a href="?video=tasarım3">Video 3: Swerve Şasenin Temelleri </a></li>
                <li><a href="?video=tasarım4">Video 4: Swerve Şasenin Tamamlanması</a></li>

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
