<?php
session_start(); // Oturumu başlat
require '../includes/dbconnection.php';  // Veritabanı bağlantısı

// Oturum kontrolü ve kullanıcı id'si
if (!isset($_SESSION['user_id'])) {
    header("Location: ../includes/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];  // Kullanıcı ID'si

// Kullanıcının izlediği dersler ve ilerleme durumlarını çekme
$query = "SELECT content_name, status FROM user_progress WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ana Sayfa</title>
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>
    <header class="navbar">
        <div class="container">
            <h1 class="logo">FRC Academy</h1>
            <nav>
                <ul class="nav-links">
                    <?php 
                    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') : ?>
                        <li><a href="admin_panel.php">Admin Paneli</a></li>
                    <?php endif; ?>
                    <li><a href="../dashboard/dashboard.php">Dashboard</a></li>
                    <li><a href="../includes/logout.php">Çıkış Yap</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-container">
        <section class="lessons-and-reports">
            <h2>Güncellemeler</h2>
            <div class="content-box">
               
            <h3>Sitemiz Beta Olarak Yayınlanmıştır İyi Kullanımlar</h3>
            <p>Sitemizin Sınavları,Rapır Sitemi Ve Dil Çeviri Özellikleri Bir Sonraki Güncellemede Eklenecektir</p>
            <a href="../feedback/feedback.php">Geri Bildirim</a>
            </div>
        </section>

        <aside class="sidebar">
            <h3>Dersler</h3>
            <ul class="sidebar-links">
                <li><a href="../egitim/yazılım.php">Yazılım</a></li>
                <li><a href="../egitim/mekanik.php">Mekanik</a></li>
                <li><a href="../egitim/elektronik.php">Elektronik</a></li>
                <li><a href="../egitim/tasarım.php">Tasarım</a></li>
                <h3>Ek Kaynaklar</h3>
                <li><a href="https://www.fikretyukselfoundation.org/hakkimizda/">Fikret Yüksel Vakfı Hakkında</a></li>
                <li><a href="https://www.frcturkiye.org/kutuphane/takim-kurma-rehberi/duyarli-profesyonellik-gracious-professionalism/">Duyarlı Profesyonellik Hakkında</a></li>
                <li><a href="https://www.frcturkiye.org">FRC Hakkında</a></li>
                <li><a href="https://firstfrc.blob.core.windows.net/frc2024/Manual/TranslatedManuals/2024GameManual-TR.pdf">2024 GameManual</a></li>
            </ul>
        </aside>
    </main>
</body>
</html>
