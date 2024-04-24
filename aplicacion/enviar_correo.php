<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="styles_2.css">
</head>
<body class="body">
    <div class="wrapper">
        <h2>Recuperar Contraseña</h2><br>
        <form action="enviar_correo.php" method="post">
            <label for="email">Introduce tu Correo Electrónico:</label><br>
            <input type="email" id="email" name="email" required><br>
            <button type="submit">Enviar Correo</button>
        </form>
    </div>
</body>
</html>

<?php

require_once 'db_connection.php';
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $get_user_id_sql = "SELECT id FROM usuarios WHERE correo_electronico = ?";
    $stmt_get_user_id = $conn->prepare($get_user_id_sql);
    $stmt_get_user_id->bind_param("s", $email);
    $stmt_get_user_id->execute();
    $result_user_id = $stmt_get_user_id->get_result();

    if ($result_user_id->num_rows === 1) {
        $row_user_id = $result_user_id->fetch_assoc();
        $id_usuario = $row_user_id["id"];

        $token = bin2hex(random_bytes(16)); 

        $sql_insert_token = "INSERT INTO tokens (id_usuario, correo_electronico, token, expira) VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))";
        $stmt_insert_token = $conn->prepare($sql_insert_token);
        $stmt_insert_token->bind_param("iss", $id_usuario, $email, $token);
        $stmt_insert_token->execute();

        $enlace = "http://localhost/aplicacion/restablecer_contraseña.php?token=" . $token;

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'marlondaza2003@gmail.com'; 
        $mail->Password = 'nzolasaltmddgplo'; 
        $mail->SMTPSecure = 'ssl'; 
        $mail->Port = 465; 
        $mail->setFrom('tu_correo@example.com', 'Tu Nombre');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Recuperación de Contraseña';
        $mail->Body = 'Hola,<br><br>Para restablecer tu contraseña, haz clic en el siguiente enlace:<br><br><a href="' . $enlace . '">Restablecer Contraseña</a><br><br>Este enlace expirará en 1 hora.<br><br>Si no solicitaste restablecer tu contraseña, puedes ignorar este correo.<br><br>Saludos.';

        if ($mail->send()) {
            echo "Se ha enviado un correo electrónico con instrucciones para restablecer tu contraseña.";
        } else {
            echo "Error al enviar el correo electrónico: " . $mail->ErrorInfo;
        }
    } else {
        echo "No se encontró un usuario con el correo electrónico proporcionado.";
    }
}
?>
