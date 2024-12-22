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
$video_src = '../video/Yazılım/1.ders_full.mov';
$video_title = "Yazılım 1. Bölüm - Javaya Giriş";
$video_description = "Bu Videoda Javaya Giriş Yaptık";
$detailed_description = "";
$lesson_files = [
    ['name' => 'Java Kurulum', 'link' => 'https://youtu.be/ZsRqUwqnMEE?si=XRfXD8lHe1BkwrjJ'],
];

// GET parametresi ile video seçimi
if (isset($_GET['video'])) {
    $video_id = htmlspecialchars($_GET['video']);
    switch ($video_id) {
        case 'yazılım1':
            $video_src = '../video/Yazılım/1.ders_full.mov';
            $video_title = "Yazılım 1. Bölüm - Javaya Ders 1";
            $video_description = "Bu Videoda Javaya Giriş Yaptık.";
            $detailed_description = "Visual Studio Code özelliklerine değinildi ve dosya açılımı gösterildi. Veri tipleri işlendi. İlk olarak, byte, short, int ve long gibi ilkel veri tipleri tanıtıldı ve bunlar tam sayı verileri için kullanılır. Ardından, float ve double veri tipleri ele alındı; bunlar ondalıklı sayılar için kullanılır ve float ile tanımlama yapılırken içine F harfi eklenmesi gerektiği belirtildi. String veri tipi harfleri tanımlamak için kullanılırken, char veri tipi ise yalnızca tek bir harf tanımlamak için kullanılır. Son olarak, boolean veri tipi true ve false değerlerini alır. Gördüğümüz tüm bu veri tipleri, System.out.println() ile yazdırılmayı öğrenildi.";
            $lesson_files = [
                ['name' => 'Java Kurulum', 'link' => 'https://youtu.be/ZsRqUwqnMEE?si=XRfXD8lHe1BkwrjJ'],
            ];
            break;
        case 'yazılım2':
            $video_src = '../video/Yazılım/2.ders_full.mov';
            $video_title = "Yazılım 2. Bölüm - Java Ders 2 ";
            $video_description = "Bu video, Pneumatic çalışma prensiplerini ve uygulamalarını açıklar.";
            $detailed_description = "
Bugünkü derste if, if-else ve else yapıları işlendi. If bir koşuldur ve koşul doğru olduğunda if bloğunda yazılan şey yapılır. Eğer koşul doğru değilse, if-else yapısına bakılır; burada koşul doğruysa if-else bloğunda belirtilen işlem yapılır, eğer hala doğru değilse, else bloğundaki işlem yapılır. Ayrıca, fonksiyon oluşturma konusu işlendi. Fonksiyonlar belirli bir işlemi yapmak için tanımlanır ve çağrıldığında o işlemi yerine getirir.";
            $lesson_files = [
                ['name' => 'Java Kurulum', 'link' => 'https://youtu.be/ZsRqUwqnMEE?si=XRfXD8lHe1BkwrjJ'],
            ];
  
            break;
        case 'yazılım3':
            $video_src = '../video/Yazılım/3.ders_full.mov';
            $video_title = 'Yazılım 3. Bölüm - Java Ders 3';
            $video_description = "Bu video, PDH'ın mantığını ve devrelerde nasıl kullanılacağını öğretir.";
            $detailed_description = "
Bu dersimizde fonksiyonları kullanmayı ve constructor (yapıcı metod) kavramını işledik. Fonksiyonlar, belirli bir işlemi gerçekleştiren, genellikle birden fazla yerde kullanılabilecek kod bloklarıdır. Fonksiyonlar tanımlanırken, hangi verileri alacağı ve hangi türde bir sonuç döndüreceği belirlenir. Fonksiyonlar, tekrarlanan işlemleri daha kolay ve düzenli hale getirmek için kullanılır. Fonksiyon tanımlandıktan sonra, o fonksiyonu çağırarak işlemi gerçekleştirebiliriz.
Constructor (yapıcı metod), bir sınıfın (class) nesnesi oluşturulduğunda otomatik olarak çalışan özel bir metoddur. Constructor, sınıfın üyelerini başlatmak veya başlangıç değerleri atamak için kullanılır. Her sınıfın bir constructor’ı olabilir ve eğer sınıfta bir constructor tanımlanmazsa, varsayılan olarak boş bir constructor kullanılır. Constructor, sınıfın nesnesi yaratıldığında çalışır ve genellikle sınıfın özelliklerini ilk değerlerle başlatmak için kullanılır.
Bu derste öğrendiğimiz, fonksiyonların tekrar kullanılabilirliğini arttıran güçlü araçlar olduğu ve constructor’ların sınıf nesnelerinin oluşturulması için gerekli temel yapı taşları olduğu bilgisiydi.";
            $lesson_files = [
                ['name' => 'Java Kurulum', 'link' => 'https://youtu.be/ZsRqUwqnMEE?si=XRfXD8lHe1BkwrjJ'],
            ];
            break;

            case 'yazılım4':
                $video_src = '../video/Yazılım/4.ders_full.mov';
                $video_title = 'Yazılım 4. Bölüm - WPI Tank Drive ';
                $video_description = "Bu derste, WPILib kütüphanesini kullanarak bir Tank Drive sistemi için temel bir kod yazmayı öğrendik";
                $detailed_description = "Bu derste WPILib kullanarak bir proje açma işlemi gösterildi. WPILib, FRC robotları için yazılım geliştirmeye yönelik bir kütüphanedir ve robotların donanım bileşenleriyle etkileşim kurmamızı sağlar. Proje açıldıktan sonra, tank şasi için robot sürücülerinin kod yazımı gösterildi. Tank şasi, iki motorun her biriyle bağımsız olarak sağ ve sol tarafları kontrol etmeye yarar. Bu şasi türü için motorları kontrol eden temel kod yazıldı.
Ayrıca, controller atama konusu işlendi. FRC robotlarında, sürücülerin robotu kontrol edebilmesi için kontrolcülerin doğru şekilde ataması yapılmalıdır. Bu dersle birlikte, kontrolcülerin robot sürücülerine atanması ve kontrolcüleri kullanarak robotu yönlendirme işlemi gösterildi.
Son olarak, compressor kullanımına değinildi. Compressor, robotun hava sistemlerini çalıştıran bir bileşendir ve WPILib üzerinden doğru şekilde nasıl kontro";
                $lesson_files = [
                    ['name' => 'WPILıb Kurulum', 'link' => 'https://docs.wpilib.org/en/stable/docs/zero-to-robot/step-2/wpilib-setup.html'],
                ];
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

                    <h3>Ders Materyalleri:</h3>
                    <ul>
                        <?php foreach ($lesson_files as $file): ?>
                            <li><a href="<?php echo htmlspecialchars($file['link']); ?>" target="_blank"><?php echo htmlspecialchars($file['name']); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Dersler Bölümü Sağda -->
        <div class="lessons">
            <h2>Dersler</h2>
            <ul>
                <li><a href="?video=yazılım1">Video 1: Javaya Giriş </a></li>
                <li><a href="?video=yazılım2">Video 2: Java Ders 2 </a></li>
                <li><a href="?video=yazılım3">Video 3: Java Ders 3</a></li>
                <li><a href="?video=yazılım4">Video 4: WPI Tank Drive </a></li>
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