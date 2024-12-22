<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require '../includes/dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form verilerini al
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Şifreyi hash'le
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Veritabanına yeni admin ekle
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
    $stmt->bind_param("sss", $name, $email, $hashed_password);

    if ($stmt->execute()) {
        $message = "Admin hesabı başarıyla oluşturuldu.";
    } else {
        $error = "Hesap oluşturulurken bir hata oluştu.";
    }

    // Veritabanı bağlantısını kapat
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Hesap Oluştur</title>
    <link rel="stylesheet" href="../css/admin_create.css">
</head>
<body>

    <div class="navbar">
        <a href="main.php">Ana Sayfa</a>
        <a href="admin_panel.php">Başvurular</a>
        <a href="kayıtlıüyeler.php">Üyeler</a>
        <a href="../includes/logout.php">Çıkış Yap</a>

    </div>

    <div class="admin-container">
    <center>  <h1>Yeni Admin Hesabı Oluştur</h1></center>  
    <br>

        <?php if (isset($message)) { echo "<p class='success'>$message</p>"; } ?>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

        <form action="admin_create.php" method="POST">
            <label for="name">Ad Soyad:</label>
            <input type="text" name="name" required><br><br>

            <label for="email">Email:</label>
            <input type="email" name="email" required><br><br>

            <label for="password">Şifre:</label>
            <input type="password" name="password" required><br><br>

            <button type="submit">Admin Hesabı Oluştur</button>
        </form>
    </div>

</body>
</html>
