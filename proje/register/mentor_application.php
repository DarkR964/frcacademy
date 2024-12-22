<?php
// Veritabanı bağlantısı
require '../includes/dbconnection.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Formdan gelen veriler
    $team_number = $_POST['team_number'];
    $team_name = $_POST['team_name'];
    $frc_years = $_POST['frc_years'];
    $mentor_name = $_POST['mentor_name'];
    $mentor_email = $_POST['mentor_email'];

    // Öğrenciler bilgisi kontrolü
    if (isset($_POST['students']) && is_array($_POST['students'])) {
        $students = json_encode($_POST['students']); // Öğrencilerin JSON formatında kaydedilmesi

        // Takım bilgisini "teams" tablosuna ekle
        $stmt = $conn->prepare("INSERT INTO teams (team_number, name) VALUES (?, ?)");
        $stmt->bind_param("is", $team_number, $team_name);

        if ($stmt->execute()) {
            // Takımın eklenmesinin ardından takımın id'sini al
            $team_id = $stmt->insert_id; // Son eklenen takımın id'si

            echo "<script>alert('Takım bilgileri başarıyla kaydedildi.');</script>";

            // Başvuru verilerini "mentor_applications" tablosuna ekle
            $stmt = $conn->prepare("INSERT INTO mentor_applications (team_id, team_number, team_name, frc_years, mentor_name, mentor_email, students, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
            $stmt->bind_param("iisssss", $team_id, $team_number, $team_name, $frc_years, $mentor_name, $mentor_email, $students);

            if ($stmt->execute()) {
                echo "<script>alert('Mentör başvurusu başarıyla kaydedildi.');</script>";

                // Başvuru JSON dosyasına kaydet
                $json_file = '../json/mentor_applications.json'; 
                $existing_data = [];


                // Yeni başvuru verilerini JSON'a ekle
                $new_application = [
                    'team_number' => $team_number,
                    'team_name' => $team_name,
                    'frc_years' => $frc_years,
                    'mentor_name' => $mentor_name,
                    'mentor_email' => $mentor_email,
                    'students' => $_POST['students'], 
                ];

                $existing_data[] = $new_application;

                if (file_put_contents($json_file, json_encode($existing_data, JSON_PRETTY_PRINT))) {
                    echo "<script>alert('Öğrenci bilgileri başarıyla kaydedildi.');</script>";
                } else {
                    echo "<script>alert('Öğrenci bilgileri kaydedilirken bir hata oluştu.');</script>";
                }
            } else {
                echo "<script>alert('Mentör başvurusu sırasında bir hata oluştu.');</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Takım bilgileri kaydedilirken bir hata oluştu.');</script>";
        }
    } else {
        echo "<script>alert('Öğrenci bilgileri eksik.');</script>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentör Başvuru Formu</title>
    <link rel="stylesheet" href="../css/mentör.css">
    <link rel="icon" href="../images/frcacademy.jpg" type="image/x-icon" />
</head>
<body>

    <div class="form-container">
        <h2>Mentör Başvuru Formu</h2>
        
        <form method="POST">
            <div class="form-row">
                <div class="input-group">
                    <label for="team_number">Takım Numarası:</label>
                    <input type="number" name="team_number" required>
                </div>
                <div class="input-group">
                    <label for="team_name">Takım İsmi:</label>
                    <input type="text" name="team_name" required>
                </div>
            </div>

            <div class="form-row">
                <div class="input-group">
                    <label for="frc_years">Kaç Yıldır FRC'de:</label>
                    <input type="number" name="frc_years" min="1" required>
                </div>
                <div class="input-group">
                    <label for="mentor_name">Mentör Adı Soyadı:</label>
                    <input type="text" name="mentor_name" required>
                </div>
            </div>

            <div class="form-row">
                <div class="input-group">
                    <label for="mentor_email">Mentör Email:</label>
                    <input type="email" name="mentor_email" required>
                </div>
                <div class="input-group">
                    <label for="students_count">Öğrenci Sayısı:</label>
                    <input type="number" name="students_count" id="students_count" min="1" required>
                </div>
            </div>

            <div class="form-row">
                <button type="button" onclick="createStudentFields()">Öğrenci Alanlarını Oluştur</button>
            </div>

            <div id="students_container" class="students-container"></div>

            <div class="form-row">
                <button type="submit">Başvur</button>
            </div>
        </form>
    </div>

    <script src="../js/mentor_application.js"></script>

</body>
</html>

<script>
function createStudentFields() {
    var count = parseInt(document.getElementById('students_count').value);
    var container = document.getElementById('students_container');
    container.innerHTML = ''; 

    if (!isNaN(count) && count > 0) { 
        for (var i = 0; i < count; i++) {
            var studentDiv = document.createElement('div');
            studentDiv.innerHTML = `
                <fieldset>
                    <legend>Öğrenci ${i + 1}</legend>

                    <label for="student_name_${i}">Öğrenci Adı:</label>
                    <input type="text" name="students[${i}][name]" required>
                    
                    <label for="student_surname_${i}">Öğrenci Soyadı:</label>
                    <input type="text" name="students[${i}][surname]" required>

                    <label for="student_email_${i}">Öğrenci Email:</label>
                    <input type="email" name="students[${i}][email]" required>

                    <label for="student_frc_years_${i}">Kaç Yıldır FRC'de?</label>
                    <input type="number" name="students[${i}][frc_years]" min="0" required>

                    <label for="student_department_${i}">Ders Almak İstediği Bölüm:</label>
                    <select name="students[${i}][department]" required>
                        <option value="">Bölüm Seçin</option>
                        <option value="software">Yazılım</option>
                        <option value="mechanical">Mekanik</option>
                        <option value="electronics">Elektronik</option>
                        <option value="design">Tasarım</option>
                    </select>
                </fieldset>
            `;
            container.appendChild(studentDiv);
        }
    } else {
        alert("Lütfen geçerli bir öğrenci sayısı girin.");
    }
}
</script>
