<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="styles_2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="body">
    <div class="wrapper">
        <h1>Iniciar Sesión</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="email" name="email" placeholder="Email">
            <input type="password" name="password" placeholder="Password">
            <div class="recover">
                <a href="enviar_correo.php">¿Olvidó su contraseña?</a>
            </div>
            <button type="submit">Ingresar</button>
        </form>
        <div class="member">
            ¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a>            
        </div>
    </div>

<?php
session_start();


include("db_connection.php");

if (isset($_SESSION['id'])) {
    header("Location: dashboard.php");
    die(); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, correo_electronico, contraseña FROM usuarios WHERE correo_electronico = ?");
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $stmt->bind_param("s", $email);

    if ($stmt->execute() === false) {
        die("Error al ejecutar la consulta: " . $stmt->error);
    }

    $stmt->bind_result($id, $correo, $passwordHash);

    $stmt->fetch();

    if ($correo !== null && password_verify($password, $passwordHash)) {
        $_SESSION['id'] = $id; 
        header("Location: dashboard.php");
        exit(); 
    } else {
        echo "Correo electrónico o contraseña incorrectos. Por favor, inténtalo de nuevo.";
    }

    $stmt->close();
}
?>



</body>
</html>
