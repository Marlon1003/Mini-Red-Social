<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link rel="stylesheet" href="styles_2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="body">
<div class="wrapper">
        <h1>Registrarse</h1>
        <form action="register.php" method="post">
            <input type="text" name="username" placeholder="Username">
            <input type="email" name="email" placeholder="Email">
            <input type="password" name="password" placeholder="Password">
            <div class="terms">
                <label for="checkbox">Estoy de acuerdo con los</label>
                <p>Términos y Condiciones</p>
                <input type="checkbox" id="checkbox">
            </div>
            <button type="submit">Registrarse</button>
        </form>
        <div class="member">¿Ya tienes una cuenta?<a href="login.php"> Accede aquí</a></div>
    </div>

    <?php
session_start();
include_once("db_connection.php");
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$username_regex = "/^[a-zA-Z0-9_]{3,20}$/";
$email_regex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/"; 
$password_regex = "/^(?=.*[0-9].*[0-9])[a-zA-Z0-9]{8,}$/";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!preg_match($username_regex, $username)) {
        echo "El nombre de usuario debe contener de 3 a 20 caracteres alfanuméricos o guiones bajos.";
        exit;
    }

    if (!preg_match($email_regex, $email)) {
        echo "El correo electrónico no es válido.";
        exit;
    }

    if (!preg_match($password_regex, $password)) {
        echo "La contraseña debe tener al menos 8 caracteres más dos números.";
        exit;
    }

    $sql_check_username = "SELECT id FROM usuarios WHERE nombre_usuario = ?";
    $stmt_check_username = mysqli_prepare($conn, $sql_check_username);
    mysqli_stmt_bind_param($stmt_check_username, "s", $username);
    mysqli_stmt_execute($stmt_check_username);
    mysqli_stmt_store_result($stmt_check_username);
    if (mysqli_stmt_num_rows($stmt_check_username) > 0) {
        echo "Error: El nombre de usuario ya está en uso.";
        exit;
    }
    mysqli_stmt_close($stmt_check_username);

    $sql_check_email = "SELECT id FROM usuarios WHERE correo_electronico = ?";
    $stmt_check_email = mysqli_prepare($conn, $sql_check_email);
    mysqli_stmt_bind_param($stmt_check_email, "s", $email);
    mysqli_stmt_execute($stmt_check_email);
    mysqli_stmt_store_result($stmt_check_email);
    if (mysqli_stmt_num_rows($stmt_check_email) > 0) {
        echo "Error: El correo electrónico ya está en uso.";
        exit;
    }
    mysqli_stmt_close($stmt_check_email);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nombre_usuario, correo_electronico, contraseña, fecha_creacion, fecha_actualizacion) 
            VALUES ('$username', '$email', '$hashed_password', NOW(), NOW())";

    if (mysqli_query($conn, $sql)) {
        $mail = new PHPMailer();
        
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'marlondaza2003@gmail.com'; 
        $mail->Password = 'nzolasaltmddgplo'; 
        $mail->SMTPSecure = 'ssl'; 
        $mail->Port = 465; 
        $mail->setFrom('tu_correo@example.com', 'Tu Nombre');
        $mail->addAddress($email, $username);
        $mail->isHTML(true);
        $mail->Subject = '¡Bienvenido a Nuestra Aplicación!';
        $mail->Body = '¡Hola ' . $username . ', bienvenido a nuestra aplicación! Esperamos que disfrutes tu experiencia.';

        if ($mail->send()) {
            echo "Registro exitoso. ¡Bienvenido! Correo de bienvenida enviado correctamente.";
        } else {
            echo "Error al enviar el correo de bienvenida: " . $mail->ErrorInfo;
        }
    } else {
        echo "Error al registrar el usuario: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
}
?>

</body>
</html>
