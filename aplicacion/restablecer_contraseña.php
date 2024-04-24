<?php
require_once 'db_connection.php';

if (isset($_GET["token"])) {
    $token = $_GET["token"];
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Restablecer Contraseña</title>
        <link rel="stylesheet" href="styles_2.css">
    </head>
    <body class="body">
        <div class="wrapper">
            <h2>Restablecer Contraseña</h2><br>
            <form action="actualizar_contraseña.php" method="post">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <label for="nueva_contrasena">Introduce una nueva contraseña:</label><br>
                <input type="password" id="nueva_contrasena" name="nueva_contrasena" required><br>
                <button type="submit">Restablecer</button>
            </form>
        </div>
    </body>
    </html>
    <?php
} else {
    echo "Token inválido.";
}
?>
