<?php
session_start(); 

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require '../includes/dbconnection.php'; 

if (isset($_POST['action'])) {
    $application_id = $_POST['application_id'];
    $action = $_POST['action'];

    // Başvuru bilgilerini alıyoruz
    $stmt = $conn->prepare("SELECT * FROM mentor_applications WHERE id = ?");
    $stmt->bind_param("i", $application_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $application = $result->fetch_assoc();
    $stmt->close(); 

    if ($application) {
        if ($action == 'approve') {
            $team_number = $application['team_number'];
            $team_name = $application['team_name'];
            $mentor_name = $application['mentor_name'];
            $mentor_email = $application['mentor_email'];
            $students = json_decode($application['students'], true);

            // Şifre oluştur ve hash'le
            $password = strtolower($team_name) . $team_number;
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt_check_mentor = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $stmt_check_mentor->bind_param("s", $mentor_email);
            $stmt_check_mentor->execute();
            $stmt_check_mentor->bind_result($count);
            $stmt_check_mentor->fetch();
            $stmt_check_mentor->close(); 

            if ($count == 0) {
                $stmt_mentor = $conn->prepare("INSERT INTO users (role, team_number, name, email, password) VALUES ('mentor', ?, ?, ?, ?)");
                $stmt_mentor->bind_param("isss", $team_number, $mentor_name, $mentor_email, $hashed_password);
                $stmt_mentor->execute();
                $stmt_mentor->close(); 
            }

            foreach ($students as $student) {
                $student_name = $student['name'] . ' ' . $student['surname'];
                $student_email = $student['email'];

                $stmt_check_student = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
                $stmt_check_student->bind_param("s", $student_email);
                $stmt_check_student->execute();
                $stmt_check_student->bind_result($count);
                $stmt_check_student->fetch();
                $stmt_check_student->close(); 

                if ($count == 0) {
                    $stmt_student = $conn->prepare("INSERT INTO users (role, team_number, name, email, password) VALUES ('student', ?, ?, ?, ?)");
                    $stmt_student->bind_param("isss", $team_number, $student_name, $student_email, $hashed_password);
                    $stmt_student->execute();
                    $stmt_student->close(); 
                }
            }

            // Başvuruyu onayla
            $stmt_update = $conn->prepare("UPDATE mentor_applications SET status = 'approved' WHERE id = ?");
            $stmt_update->bind_param("i", $application_id);
            $stmt_update->execute();
            $stmt_update->close(); 

            echo "<script>alert('Başvuru onaylandı ve hesaplar oluşturuldu!');</script>";
        } elseif ($action == 'reject') {
            // Başvuruyu reddet
            $stmt_update = $conn->prepare("UPDATE mentor_applications SET status = 'rejected' WHERE id = ?");
            $stmt_update->bind_param("i", $application_id);
            $stmt_update->execute();
            $stmt_update->close(); 

            echo "<script>alert('Başvuru reddedildi!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli</title>
    <link rel="stylesheet" href="../css/admin_panel.css">


</head>
<body>
    <div class="admin-container">
        <header>
            <h1>Admin Paneli</h1>
            <nav>
                <ul>
                    <li><a href="main.php">Ana Sayfa</a></li>
                    <li><a href="kayıtlıüyeler.php">Üyeler</a></li>
                    <li><a href="admin_create.php">Admin Ekleme</a></li>
                    <li><a href="../includes/logout.php">Çıkış Yap</a></li>
                </ul>
            </nav>
        </header>

        <h2>Mentör Başvuruları</h2>
        <div class="table-container">
            <table class="application-table">
                <thead>
                    <tr>
                        <th>Takım Numarası</th>
                        <th>Takım İsmi</th>
                        <th>Mentör İsmi</th>
                        <th>Mentör E-posta</th>
                        <th>Öğrenci Bilgileri</th>
                        <th>Durum</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conn->prepare("SELECT * FROM mentor_applications WHERE status = 'pending'");
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()) {
                        $students = json_decode($row['students'], true);
                        $student_info = "<ul>";
                        if (is_array($students)) {
                            foreach ($students as $student) {
                                $student_info .= "<li>Ad: " . htmlspecialchars($student['name']) . " " . 
                                                 "Soyad: " . htmlspecialchars($student['surname']) . ", " . 
                                                 "E-posta: " . htmlspecialchars($student['email']) . "</li>";
                            }
                        } else {
                            $student_info .= "<li>Öğrenci bilgisi yok.</li>";
                        }
                        $student_info .= "</ul>";

                        echo "<tr>
                                <td>" . htmlspecialchars($row['team_number']) . "</td>
                                <td>" . htmlspecialchars($row['team_name']) . "</td>
                                <td>" . htmlspecialchars($row['mentor_name']) . "</td>
                                <td>" . htmlspecialchars($row['mentor_email']) . "</td>
                                <td>" . $student_info . "</td>
                                <td>" . htmlspecialchars($row['status']) . "</td>
                                <td>
                                    <form method='POST' style='display:inline;'>
                                        <input type='hidden' name='application_id' value='" . $row['id'] . "'>
                                        <button type='submit' name='action' value='approve' onclick='return confirm(\"Onaylamak istediğinize emin misiniz?\")'>Onayla</button>
                                    </form>
                                    <form method='POST' style='display:inline;'>
                                        <input type='hidden' name='application_id' value='" . $row['id'] . "'>
                                        <button type='submit' name='action' value='reject' onclick='return confirm(\"Reddetmek istediğinize emin misiniz?\")'>Red Et</button>
                                    </form>
                                </td>
                              </tr>";
                    }

                    $stmt->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
