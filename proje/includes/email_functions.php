<?php
function sendApprovalEmail($email, $password) {
    $subject = "Mentör Başvurunuz Onaylandı";
    $message = "Başvurunuz onaylandı. Giriş için şifreniz: " . $password;
    $headers = "From: no-reply@frcedusite.com";

    mail($email, $subject, $message, $headers);
}
?>
