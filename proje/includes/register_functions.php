<?
function checkRole($requiredRole) {
    session_start(); 
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $requiredRole) {
        header("Location: no_access.php");
        exit();
    }
}

?>