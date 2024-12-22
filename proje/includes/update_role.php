<?
require 'dbconnection.php';
checkRole('admin'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $newRole = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $newRole, $userId);

    if ($stmt->execute()) {
        echo "Rol başarıyla güncellendi.";
    } else {
        echo "Rol güncellenirken bir hata oluştu.";
    }

    $stmt->close();
    $conn->close();
    header("Location: admin_panel.php"); 
    exit();
}
?>
