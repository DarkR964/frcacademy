<?php
// Oturum kontrolü
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Veritabanı bağlantısı
require '../includes/dbconnection.php'; 

$login_error = ""; // Hata mesajı başlangıçta boş

// Form gönderildiğinde çalışır
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Form verilerini almak
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // E-posta veya şifre boşsa hata mesajı
    if (empty($email) || empty($password)) {
        $login_error = "E-posta ve şifre boş olamaz.";
    } else {
        // Veritabanında kullanıcıyı arama
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Kullanıcı bulunduysa ve şifre doğruysa
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id']; 
            $_SESSION['user_role'] = $user['role'] ?? 'user'; 
            $_SESSION['user_email'] = $user['email']; 

            // Başarılı giriş sonrası yönlendirme
            header("Location: main.php");
            exit();
        } else {
            // Hatalı e-posta veya şifre
            $login_error = "Hatalı e-posta veya şifre.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="../css/login.css"> 
</head>
<body>
    <form action="" method="POST"> 
        <div class="login-container">
            <h2 class="login-header">Giriş Yap</h2>

            <!-- E-posta alanı -->
            <input type="email" name="email" id="email" class="input" placeholder="E-posta adresinizi girin" required>
            
            <!-- Şifre alanı -->
            <input type="password" name="password" id="password" class="input" placeholder="Şifrenizi girin" required>

            <!-- Giriş butonu -->
            <button type="submit" name="login" class="button">Giriş Yap</button>
           
            <footer>
                <!-- Kayıt ol linki -->
                <a href="../register/student_register.php">Kayıt Ol</a>
            </footer>
        </div>
    </form>

    <!-- Hata mesajı gösterimi -->
    <?php if (!empty($login_error)): ?>
        <div class="error-message"><?php echo htmlspecialchars($login_error); ?></div>
    <?php endif; ?>
</body>
</html>
