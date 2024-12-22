<?php
// Oturum başlatma
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Veritabanı bağlantısı
include '../includes/dbconnection.php'; 

// Kullanıcı giriş kontrolü
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['admin', 'mentor', 'student'])) {
    // Kullanıcı giriş yapmamış ya da geçerli bir role sahip değilse, giriş sayfasına yönlendir
    header("Location: ../php/login.php");
    exit();
}

// Oturumdan kullanıcı bilgilerini al
$userId = $_SESSION['user_id'] ?? null;
$userEmail = $_SESSION['user_email'] ?? "E-posta tanımlı değil";
$userRole = $_SESSION['user_role'] ?? "user";


// Kullanıcının bir takımda olup olmadığını kontrol et
$teamInfo = null;
if ($userId) {
    $stmt = $conn->prepare("SELECT teams.id, teams.name FROM teams 
                            INNER JOIN users ON teams.id = users.team_id 
                            WHERE users.id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $teamInfo = $result->fetch_assoc();
    }
}

// Davet etme işlemi
$inviteMessage = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && $userRole === 'mentor') {
    $email = $_POST['email'];

    // E-posta doğrulaması
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $inviteMessage = "Geçerli bir e-posta adresi giriniz.";
    } else {
        // Davet edilen e-posta ile kullanıcı olup olmadığını kontrol et
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND team_id IS NULL");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Kullanıcı var, davet et
            $user = $result->fetch_assoc();
            $userId = $user['id'];

            // Kullanıcıyı takıma ekle
            $stmt = $conn->prepare("UPDATE users SET team_id = ? WHERE id = ?");
            $stmt->bind_param("ii", $teamInfo['id'], $userId);
            if ($stmt->execute()) {
                $inviteMessage = "Kullanıcı başarıyla davet edildi.";
            } else {
                $inviteMessage = "Bir hata oluştu, tekrar deneyin.";
            }
        } else {
            $inviteMessage = "Bu e-posta adresine sahip kullanıcı bulunamadı veya zaten bir takımda yer alıyor.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
<div class="dashboard-container">
    <header>
        <h1>Dashboard</h1>
        <nav>
            <ul>
                <li><a href="../php/main.php">Anasayfa</a></li>
                <li><a href="../includes/logout.php">Çıkış Yap</a></li>
            </ul>
        </nav>
    </header>

    <section class="user-info">
        <h2>Kullanıcı Bilgileri</h2>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($userEmail); ?></p>
        <p><strong>Rol:</strong> <?php echo htmlspecialchars($userRole); ?></p>
    </section>

    <section class="team-info">
        <h2>Takım Bilgileri</h2>
        <?php if ($teamInfo): ?>
            <p><strong>Takım Adı:</strong> <?php echo htmlspecialchars($teamInfo['name']); ?></p>
            <p><strong>Takım Numarası:</strong> <?php echo htmlspecialchars($teamInfo['id']); ?></p>
        <?php else: ?>
            <p>Bir takımda değilsiniz.</p>
        <?php endif; ?>
    </section>

    <?php if ($userRole === 'mentor' && $teamInfo): ?>
        <section class="team-management">
            <h2>Takım Yönetimi</h2>

            <h3>Üyeler</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Ad</th>
                            <th>E-posta</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($teamInfo) {
                            $stmt = $conn->prepare("SELECT id, name, email FROM users WHERE team_id = ?");
                            $stmt->bind_param("i", $teamInfo['id']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while ($member = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($member['name']); ?></td>
                                    <td><?php echo htmlspecialchars($member['email']); ?></td>
                                    <td>
                                        <form action="remove_member.php" method="post" style="display:inline;">
                                            <input type="hidden" name="member_id" value="<?php echo $member['id']; ?>">
                                            <button type="submit" class="remove-btn">Üyeyi Çıkar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; 
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <br><br>
            <h3>Üye Davet Et</h3>
            <form action="dashboard.php" method="post">
                <label for="email">E-posta:</label>
                <input type="email" name="email" id="email" required>
                <button type="submit">Davet Et</button>
            </form>

            <!-- Davet Etme Mesajı -->
            <?php if ($inviteMessage): ?>
                <p class="invite-message"><?php echo htmlspecialchars($inviteMessage); ?></p>
            <?php endif; ?>

        </section>
    <?php endif; ?>

    <?php if ($userRole === 'student'): ?>
        <section class="team-management">
            <p>Öğrenci olduğunuz için üye davet etme yetkiniz yok.</p>
        </section>
    <?php endif; ?>
</div>
</body>
</html>
