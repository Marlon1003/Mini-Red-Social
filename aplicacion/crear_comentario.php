<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit; 
}

include_once("db_connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["contenido"]) && !empty($_POST["id_publicacion"])) {
        $contenido = $_POST["contenido"];
        $id_publicacion = $_POST["id_publicacion"];
        $id_usuario = $_SESSION["id"];

        $sql = "INSERT INTO comentarios (id_usuario, id_publicacion, contenido, fecha_creacion) VALUES (?, ?, ?, NOW())";

        if ($conn) {
            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "iis", $id_usuario, $id_publicacion, $contenido);

                if (mysqli_stmt_execute($stmt)) {
                    header("Location: publicacion.php?id=$id_publicacion");
                    exit();
                } else {
                    echo "Error al agregar el comentario.";
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
        echo "El contenido del comentario o el ID de la publicación no pueden estar vacíos.";
    }
} else {
    header("Location: error.php");
    exit();
}
?>;
