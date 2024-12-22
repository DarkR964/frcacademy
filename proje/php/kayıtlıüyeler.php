<?php
session_start();
require '../includes/dbconnection.php';

// Admin kontrolü
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<script>alert('Lütfen admin olarak giriş yapın.'); window.location.href='../includes/login.php';</script>";
    exit();
}

// Kullanıcıları ve takımlarını listelemek için sorgu
$query = "
    SELECT 
        u.id AS user_id, 
        u.name AS user_name, 
        u.email, 
        u.role, 
        u.team_number, 
        t.name AS team_name, 
        u.created_at 
    FROM 
        users u
    LEFT JOIN 
        teams t 
    ON 
        u.team_id = t.id
    ORDER BY 
        t.name ASC, u.team_number ASC
";
$result = $conn->query($query);

// Kullanıcı silme işlemi
if (isset($_POST['delete_user'])) {
    $user_id = intval($_POST['user_id']);
    $delete_query = "DELETE FROM users WHERE id = $user_id";
    if ($conn->query($delete_query)) {
        $_SESSION['message'] = "Kullanıcı başarıyla silindi.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION['message'] = "Kullanıcı silinirken bir hata oluştu.";
    }
}

// Kullanıcı düzenleme işlemi
if (isset($_POST['edit_user'])) {
    $user_id = intval($_POST['user_id']);
    $new_team_id = intval($_POST['team_id']);
    $update_query = "UPDATE users SET team_id = $new_team_id WHERE id = $user_id";
    if ($conn->query($update_query)) {
        $_SESSION['message'] = "Kullanıcı başarıyla güncellendi.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION['message'] = "Kullanıcı güncellenirken bir hata oluştu.";
    }
}

// Takım ekleme işlemi
if (isset($_POST['add_team'])) {
    $team_name = $_POST['team_name'];
    $team_number = $_POST['team_number'];

    // Takım numarasının benzersiz olduğundan emin olun
    $check_query = "SELECT * FROM teams WHERE team_number = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("i", $team_number);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $_SESSION['message'] = "Bu takım numarası zaten var. Lütfen farklı bir numara girin.";
    } else {
        // Yeni takım ekleme
        $team_query = "INSERT INTO teams (name, team_number) VALUES (?, ?)";
        $stmt = $conn->prepare($team_query);
        $stmt->bind_param("si", $team_name, $team_number);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Takım başarıyla eklendi.";
        } else {
            $_SESSION['message'] = "Takım eklerken bir hata oluştu.";
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli - Üyeler ve Takımlar</title>
    <link rel="stylesheet" href="../css/üyeler.css">
</head>
<body>

<div class="admin-container">
    <header>
        <h1>Admin Paneli</h1>
        <nav>
            <ul>
                <li><a href="main.php">Ana Sayfa</a></li>
                <li><a href="admin_panel.php">Başvurular</a></li>
                <li><a href="admin_create.php">Admin Ekleme</a></li>
                <li><a href="../includes/logout.php">Çıkış Yap</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <h2>Tüm Üyeler ve Takımları</h2>

        <?php
        // Başarı mesajını göster ve sonra temizle
        if (isset($_SESSION['message'])) {
            echo "<p style='color: green;'>" . $_SESSION['message'] . "</p>";
            unset($_SESSION['message']);
        }

        // Üyeler varsa, tabloyu oluştur
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr>
                    <th>ID</th>
                    <th>Ad</th>
                    <th>Email</th>
                    <th>Takım İsmi</th>
                    <th>Rol</th>
                    <th>Oluşturulma Tarihi</th>
                    <th>İşlem</th>
                  </tr>";

            // Kullanıcıları ve takım bilgilerini yazdırma
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['user_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['team_name'] ?? 'Yok') . "</td>";
                echo "<td>" . htmlspecialchars(ucfirst($row['role'])) . "</td>";
                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                echo "<td>
                        <form method='POST' style='display:inline-block;'>
                            <input type='hidden' name='user_id' value='" . htmlspecialchars($row['user_id']) . "'>
                            <button type='submit' name='delete_user' onclick='return confirm(\"Bu kullanıcıyı silmek istediğinizden emin misiniz?\")'>Sil</button>
                        </form>
                        <form method='POST' style='display:inline-block;'>
                            <input type='hidden' name='user_id' value='" . htmlspecialchars($row['user_id']) . "'>
                            <select name='team_id'>
                                <option value=''>Takım Seç</option>";

                // Takımların listesini doldurma
                $teams_query = "SELECT id, name FROM teams";
                $teams_result = $conn->query($teams_query);
                while ($team = $teams_result->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($team['id']) . "'>" . htmlspecialchars($team['name']) . "</option>";
                }

                echo "      </select>
                            <button type='submit' name='edit_user'>Düzenle</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Üye bulunmamaktadır.</p>";
        }
        ?>
    </section>


</div>

</body>
</html>
