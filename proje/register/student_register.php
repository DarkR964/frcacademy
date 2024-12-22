<?php
require '../includes/dbconnection.php'; 

// Hataları göster
error_reporting(E_ALL);
ini_set('display_errors', 1);

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);


            $json_file = '../json/mentor_applications.json'; 
            $existing_data = [];

            if (file_exists($json_file)) {
                $existing_data = json_decode(file_get_contents($json_file), true);
                if ($existing_data === null) {
                    echo "<script>alert('JSON dosyası okunurken bir hata oluştu.');</script>";
                }
            }


    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Eposta adresinin zaten kayıtlı olup olmadığını kontrol et
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = "Bu e-posta adresi zaten kayıtlı.";
    } else {

        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, is_admin, is_mentor) VALUES (?, ?, ?, 'student', 0, 0)");
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            $success_message = "Kayıt başarılı! Lütfen giriş yapın.";
        } else {
            $error_message = "Bir hata oluştu, lütfen tekrar deneyin.";
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Öğrenci Kayıt</title>
    <link rel="stylesheet" href="../css/login.css"> 
</head>
<body>
    <div class="login-container">
        <h2 class="login-header">Öğrenci Kayıt</h2>

        <?php if ($success_message): ?>
            <div class="message success"> <?php echo $success_message; ?> </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="message error"> <?php echo $error_message; ?> </div>
        <?php endif; ?>

        <form action="" method="POST" class="login">
            <input type="text" name="name" class="input" placeholder="Ad Soyad" required>
            <input type="email" name="email" class="input" placeholder="E-posta" required>
            <input type="password" name="password" class="input" placeholder="Şifre" required>
            <button type="submit" class="button">Kayıt Ol</button>
        </form>

        <footer>
        <a href="../php/login.php">Üye Girişi</a>
        </footer>
    </div>
</body>
</html>
