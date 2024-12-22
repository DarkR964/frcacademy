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
$video_src = '../video/Elektronik/RoboRIO.mov';
$video_title = "Elektronik 1. Bölüm - RoboRİO";
$video_description = "Bu video, RoboRio'nun nasıl çalıştığını ve devrelerdeki kullanımını detaylandırır.";
$detailed_description = "Bugün dersimizde size burada gördüğünüz Roborio'yu ve üzerindeki portların ne işe yaradığını göstereceğim.  
Elimdeki siyah ve kırmızı güç kablosunu, burada gördüğünüz güç portuna şu şekilde bağlıyoruz.  
Bu güç kablosunun kendine ait özel bir yeri vardır.  
CAN kablosu sarı ve yeşildir.   
CAN kablosunu düzenli bir şekilde birleştirip, tornavida kullanarak CAN girişine takıyoruz.   
Sarı alt tarafa, yeşil üst tarafa gelir.  
CAN kablosunu da bu şekilde bağlıyoruz.  
Elimdeki Ethernet kablosunu, burada gördüğünüz Ethernet portuna takıyoruz.  
Bu, elimde gördüğünüz POE kablosu.   
POE kablomuzun bir tarafında Ethernet girişi, diğer tarafında ise çıkışı vardır.  
Güç bu modeme giderken, WRM'den bağladığımız Ethernet portuna şu şekilde takıyoruz ve diğer ucu modeme bağlıyoruz.  
Modemimizin orta portunu kullanmamız daha iyi olacaktır.  
İşte bu şekilde modeme bağlamış olduk.  
Navx adını verdiğimiz cihazı, burada gördüğünüz yere takıyoruz.  
Navx'in altında bir giriş bulunuyor.  
Bunu bu şekilde takabilir ve üstten vidalarla yerleştirebilirsiniz, delikler zaten görünüyor.  
Burada gördüğünüz dijital renk sensörümüz.  
Beyaz kabloyu, sinyal portunun iç kısmındaki S tarafına takıyoruz.  
Robot sinyal ışığını, siyah altta ve kırmızı üstte olacak şekilde bağlıyoruz.  
Burada gördüğünüz relay ve analog giriş.   
Eğer sensörler analogsa, sensörleri analog girişe bağlıyoruz.  
PWM bağlantısını sağladığımız yerler burada.  
PWM, motora giden gücü sinyallerle belirler ve motorun hangi yönde döneceğini söyler.  
Yani motor sürücümüze bir PWM kablosu ile bağlıyoruz.  
Burada SparkMax'ı gösteriyorum.  
Bu şekilde, kameramızı veya USB portu olan herhangi bir cihazı burada gördüğünüz USB portlarına takabiliriz.  
Bu şekilde, kamerayı Roborio'ya bağlamış olduk ve FRC'de kullanmamıza olanak sağladık.  
Burada gördüğünüz durum LED'leri, güç LED'i, durum LED'i, radyo LED'i, iletişim LED'i, mod LED'i ve RSL LED'idir.  
En sondaki RSL LED'i, RSL'nin bağlanıp bağlanmadığını gösterir.  
Burada gördüğünüz yazıcı kablosu, üstteki yazıcı portuna takılır.  
Ethernet kablonuz doluysa, robotla kablolu bağlantı kurmamıza olanak tanır.  
USB ile bağlanmak için takabiliriz.  
Bu video için bu kadar, bir sonraki videoda görüşmek üzere.";
$lesson_files = [
    ['name' => 'RoboRio Kullanım Kılavuzu', 'link' => 'https://docs.wpilib.org/tr/stable/docs/software/roborio-info/roborio-introduction.html'],
    ['name' => 'Elektronik Devre Şeması', 'link' => 'https://docs.wpilib.org/tr/2023/_images/frc-control-system-layout-rev.svg'],
];

// GET parametresi ile video seçimi
if (isset($_GET['video'])) {
    $video_id = htmlspecialchars($_GET['video']);
    switch ($video_id) {
        case 'electronics1':
            $video_src = '../video/Elektronik/RoboRIO.mov';
            $video_title = "Elektronik 1. Bölüm - RoboRİO";
            $video_description = "Bu video, RoboRio'nun nasıl çalıştığını ve devrelerdeki kullanımını detaylandırır.";
            $detailed_description = "Bugün dersimizde size burada gördüğünüz Roborio'yu ve üzerindeki portların ne işe yaradığını göstereceğim.  
Elimdeki siyah ve kırmızı güç kablosunu, burada gördüğünüz güç portuna şu şekilde bağlıyoruz.  
Bu güç kablosunun kendine ait özel bir yeri vardır.  
CAN kablosu sarı ve yeşildir.   
CAN kablosunu düzenli bir şekilde birleştirip, tornavida kullanarak CAN girişine takıyoruz.   
Sarı alt tarafa, yeşil üst tarafa gelir.  
CAN kablosunu da bu şekilde bağlıyoruz.  
Elimdeki Ethernet kablosunu, burada gördüğünüz Ethernet portuna takıyoruz.  
Bu, elimde gördüğünüz POE kablosu.   
POE kablomuzun bir tarafında Ethernet girişi, diğer tarafında ise çıkışı vardır.  
Güç bu modeme giderken, WRM'den bağladığımız Ethernet portuna şu şekilde takıyoruz ve diğer ucu modeme bağlıyoruz.  
Modemimizin orta portunu kullanmamız daha iyi olacaktır.  
İşte bu şekilde modeme bağlamış olduk.  
Navx adını verdiğimiz cihazı, burada gördüğünüz yere takıyoruz.  
Navx'in altında bir giriş bulunuyor.  
Bunu bu şekilde takabilir ve üstten vidalarla yerleştirebilirsiniz, delikler zaten görünüyor.  
Burada gördüğünüz dijital renk sensörümüz.  
Beyaz kabloyu, sinyal portunun iç kısmındaki S tarafına takıyoruz.  
Robot sinyal ışığını, siyah altta ve kırmızı üstte olacak şekilde bağlıyoruz.  
Burada gördüğünüz relay ve analog giriş.   
Eğer sensörler analogsa, sensörleri analog girişe bağlıyoruz.  
PWM bağlantısını sağladığımız yerler burada.  
PWM, motora giden gücü sinyallerle belirler ve motorun hangi yönde döneceğini söyler.  
Yani motor sürücümüze bir PWM kablosu ile bağlıyoruz.  
Burada SparkMax'ı gösteriyorum.  
Bu şekilde, kameramızı veya USB portu olan herhangi bir cihazı burada gördüğünüz USB portlarına takabiliriz.  
Bu şekilde, kamerayı Roborio'ya bağlamış olduk ve FRC'de kullanmamıza olanak sağladık.  
Burada gördüğünüz durum LED'leri, güç LED'i, durum LED'i, radyo LED'i, iletişim LED'i, mod LED'i ve RSL LED'idir.  
En sondaki RSL LED'i, RSL'nin bağlanıp bağlanmadığını gösterir.  
Burada gördüğünüz yazıcı kablosu, üstteki yazıcı portuna takılır.  
Ethernet kablonuz doluysa, robotla kablolu bağlantı kurmamıza olanak tanır.  
USB ile bağlanmak için takabiliriz.  
Bu video için bu kadar, bir sonraki videoda görüşmek üzere.";
            $lesson_files = [
                ['name' => 'RoboRio Kullanım Kılavuzu', 'link' => 'https://docs.wpilib.org/tr/stable/docs/software/roborio-info/roborio-introduction.html'],
                ['name' => 'Elektronik Devre Şeması', 'link' => 'https://docs.wpilib.org/tr/2023/_images/frc-control-system-layout-rev.svg'],
            ];
            break;
        case 'electronics2':
            $video_src = '../video/Elektronik/Pneumatic.mov';
            $video_title = "Elektronik 2. Bölüm - Pneumatic";
            $video_description = "Bu video, Pneumatic çalışma prensiplerini ve uygulamalarını açıklar.";
            $detailed_description = "Bu videoda size pnömatik hub'ı nasıl bağlayacağınızı ve hangi portların ne işe yaradığını öğreteceğim.
Gördüğünüz gibi bu kompresör. Kompresör, pnömatik hub ile birlikte pnömatik sistemimizde kullanılır.
Kırmızı ve siyah kablolarla artı ve eksi şeklinde, gördüğünüz çıkışlardan güç sağlıyoruz.
Şimdi, videoda gösterdiğim şekilde pnömatik huba bağlayacağız.
İşte bu şekilde takılıyor.
Bu, gösterdiğim sensör.
Sensörün arkasındaki girişten, fiş dediğimiz bir şeyle güç kablosuna çeviriyoruz.
Bu güç kablosunu, yukarıda gösterildiği gibi dişli kısmına bağlıyoruz ve orada gördüğünüz girişe takıyoruz.
Bu, gördüğünüz solenoid.
Solenoid'in girişleri 2 tarafta bulunuyor. Kabloları şu şekilde, her iki tarafa birer tane takabilirsiniz.
Her birini pnömatik hub'daki belirlenen yerlere bağlayacağız.
Burada yardımcı olmak için, ince bir tornavida kullanarak kabloları üstten bastırarak mandallarını açıyoruz.
Ve videoda gördüğünüz gibi yerleştiriyoruz.
Bu ikinci kabloları, gerektiği yerlere aynı şekilde bağlıyorum.
İşte bu şekilde, pnömatik borularımızı kompresörden gelen havaya bağlıyoruz.";
            $lesson_files = [
                ['name' => 'Pneumatic ', 'link' => 'https://www.firstinspires.org/sites/default/files/uploads/resource_library/frc/technical-resources/frc_pneumatics_manual.pdf'],
            ];
            break;
        case 'electronics3':
            $video_src = '../video/Elektronik/PDH.mov';
            $video_title = 'Elektronik 3. Bölüm - PDH ';
            $video_description = "Bu video, PDH'ın mantığını ve devrelerde nasıl kullanılacağını öğretir.";
            $detailed_description = "Bugün dersimizde size PDH'nin ne olduğunu göstereceğim.
Burada gördüğünüz şey batarya girişi. Kabloları kısa süre içinde batarya girişine bağlayacağım.
Bu gördüğünüz kısım ana kesici, robotun gücünü açıp kapamamıza olanak sağlar.
Bu siyah olanı ittiğinizde açılır, kırmızı olanı ittiğinizde kapanır.
Bunu şu şekilde bağlayacağım:
siyah, siyah giriş.
Eğer kırmızıysa, kırmızı girişe bağlarım ve mandalları açarım.
Ana kesiciyi bağladık.
Burada gördüğünüz şey Roborio. Roborio'nun güç kablosunu birazdan PDH'deki bu girişe bağlayacağım.
Burada, benim gibi size yardımcı olabilecek ince bir tornavida kullanabilirsiniz.
Üstten bastırarak mandalları açıyoruz ve siyah siyaha, kırmızı kırmızıya gelecek şekilde bağlıyoruz.
Bu gördüğünüz CAN kablosu. CAN kablosunu, robotun hangi yönde döneceği gibi sinyalleri iletmek için kullanıyoruz.
CAN portuna, alt kısımdaki PDH'ye takıyoruz, yeşil yeşile, sarı sarıya olacak şekilde.
Bu gördüğünüz kablo kesici. Elimdeki 24 AWG güç kablolarını keseceğim.
Bu arada, AWG küçüldükçe kablonun kalınlığı artar.
Örneğin, batarya kablosu 6 AWG'dir ve onu gördüğünüzde 24 AWG'yi görüyorsunuz.
Burada gördüğünüz VRM. VRM'nin güç kablosu olarak 24 AWG kullanacağım.
Burada size yardımcı olabilecek ince bir tornavida kullanabilirsiniz.
Üstten bastırarak bağlayabilirsiniz.
Şimdi kırmızı için de aynısını yapacağım.
Evet, kırmızı kablomuzu sıktıktan sonra mandalı kapatarak yerine takıyoruz.
Evet, işte kablolarımızı bu şekilde bağladık.
Bu güç kablolarını bir sigorta kullanarak bağlayacağız, bunu kısa bir süre içinde göstereceğim.
Bu gördüğünüz REV'in 10 amperlik sigortası.
Bunu PDH'deki belirli bir girişe şu şekilde takıyoruz.
Ve karşısındaki mandalları açıyorum.
Eğer sol girişi takarsanız, sol mandalları güçlendirirsiniz; sağ girişi takarsanız, sağ mandalları güçlendirirsiniz.
Bu sigortalar, elektronik cihazlarınızın ömrünü uzatır ve diğer her şeyi korur.
Burada gördüğünüz gibi, güç kablolarımı kırmızı kırmızıya, siyah siyaha bağlayarak VRM'ye 10 amperlik güç sağlıyoruz.
Burada gördüğünüz POE kablosu, POE kablomuz, modemimize gidiyordu.
Ethernet portundan, bu modemi besleyecek şekilde bağlantı sağlıyoruz.
Bunu VRM'nin 12 volt-2 amper kısmına şu şekilde bağlıyorum.
Her zaman kırmızı kırmızıya, siyah siyaha takıyoruz.
Evet, burada gördüğünüz şey pnömatik hub.
Şu an sadece güç ve CAN kablolarını nasıl bağlayacağınızı göstereceğim, daha ayrıntılı olarak bir sonraki dersimizde gösteririm.
Güç kablolarımı sağ alttaki kırmızı ve siyah portlara takıyorum.
Kablolarımı bu şekilde takıyorum.
Gördüğünüz CAN kablosu, önce pnömatik huba gitmeli, bu yüzden PDH'deki kabloyu çıkarıp pnömatik huba takıyorum.
Yine üstten bastırarak takıyoruz, sarı sarıya, yeşil yeşile gelecek şekilde.
CAN kablosunu taktım.
Şimdi çıkış CAN kablosunu yan taraftaki girişe takıyorum, sarı sarıya, yeşil yeşile.
İki CAN kablo girişi olduğu için bunlar:
CAN kabloları, verileri bir elektronik cihazdan diğerine iletmek için hem giriş hem çıkış kullanır.
Buradan gelen kabloyu PDH'ye kullanacağız.
Yine sarı sarıya, yeşil yeşile girilecek şekilde takacağız.
Gördüğünüz şey sim motoru. Sim motoru bir fırçalı motordur, iki girişi vardır, biri kırmızı diğeri siyah.
Bu gördüğünüz sparmax. Sparkmax, motor sürücüsüdür çünkü 3 çıkışı vardır: hem fırçalı hem fırçasız motoru çalıştırabilir.
Kullanacağımız kırmızı ve siyah kablo budur.
Beyaz kabloyu kullanmıyorsak, kablomuzu burada gördüğünüz gibi bantlarız.
Bu gördüğünüz vago. Bunlar 12 AWG vago. Bu vagoları 2 kabloyu bağlamak için kullanacağız.
Onlar da tıpkı PDH'deki gibi mandallıdır.
Kablomuzu sıktıktan sonra vagoya yerleştirip üst mandalı kapatacağız.
Evet, üst mandalı kapattık. Aynı işlemi kırmızı için de takip ediyorum.
Kırmızı kablomuzu da bağladık.
Şimdi bunları sim motoruna vago aracıyla bağlayacağım.
İlk olarak siyah kabloyu sıkıp siyah vagoya takıyorum.
Dışarıda hiç kablo kalmadığından emin olun çünkü elektrik bu kabloların üzerinden geçecektir ve bu kabloların ısınma olasılığı vardır.
Gördüğünüz gibi kabloyu vagoya bağlıyorum ve kırmızı için aynı işlemi takip ediyorum.
Evet, bu şekilde bağladık.
Sparkmax'i PDH'ye bağlayacağım.
Burada gördüğünüz 40 amperlik REV sigortası. Bu sigortayı, PDH'deki sigorta girişine takıyorum.
Burada alt sağ tarafı tercih ediyorum.
Burada karşılık gelen mandalları açıp sparkmax'i bağlıyorum.
Mandalları şu şekilde açıyorum ve kırmızı kırmızıya, siyah siyaha gelecek şekilde bağlıyorum.
Motorumuz şimdi kullanıma hazır.
Bu şekilde gerekli devre eğitimimizi tamamlamış olduk.
Bir sonraki derste görüşmek üzere.";
            $lesson_files = [
                ['name' => 'PDH ', 'link' => 'https://docs.wpilib.org/tr/stable/docs/controls-overviews/control-system-hardware.html#rev-power-distribution-hub'],
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
    <title>Elektronik Eğitimi</title>
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
                <li><a href="?video=electronics1">Video 1: RoboRio</a></li>
                <li><a href="?video=electronics2">Video 2: Pneumatic</a></li>
                <li><a href="?video=electronics3">Video 3: PDH</a></li>
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
