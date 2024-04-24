<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit; 
}

include_once("db_connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["contenido"])) {
        $contenido = $_POST["contenido"];

        $id_usuario = $_SESSION["id"];

        $sql = "INSERT INTO publicaciones (id_usuario, contenido, fecha_creacion, fecha_actualizacion) VALUES (?, ?, NOW(), NOW())";

        if ($conn) {
            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "is", $id_usuario, $contenido);

                if (mysqli_stmt_execute($stmt)) {
                    header("Location: dashboard.php");
                    exit();
                } else {
                    echo "Error al publicar la publicación.";
                }

                mysqli_stmt_close($stmt);
            } else {
                echo "Error en la preparación de la declaración SQL.";
            }
        } else {
            echo "Error en la conexión a la base de datos.";
        }

        mysqli_close($conn);
    } else {
        echo "El contenido de la publicación no puede estar vacío.";
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>;
