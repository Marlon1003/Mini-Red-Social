<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit; 
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_publicacion"])) {
    $id_publicacion = $_POST["id_publicacion"];

    $id_usuario = $_SESSION["id"];

    include_once("db_connection.php");

    if ($conn) {
        mysqli_begin_transaction($conn);

        $sql_eliminar_comentarios = "DELETE FROM comentarios WHERE id_publicacion = ?";

        $stmt_eliminar_comentarios = mysqli_prepare($conn, $sql_eliminar_comentarios);
        mysqli_stmt_bind_param($stmt_eliminar_comentarios, "i", $id_publicacion);
        $resultado_eliminar_comentarios = mysqli_stmt_execute($stmt_eliminar_comentarios);

        $sql_eliminar_publicacion = "DELETE FROM publicaciones WHERE id = ? AND id_usuario = ?";
        
        $stmt_eliminar_publicacion = mysqli_prepare($conn, $sql_eliminar_publicacion);
        mysqli_stmt_bind_param($stmt_eliminar_publicacion, "ii", $id_publicacion, $id_usuario);
        $resultado_eliminar_publicacion = mysqli_stmt_execute($stmt_eliminar_publicacion);

        if ($resultado_eliminar_comentarios && $resultado_eliminar_publicacion) {
            mysqli_commit($conn);
            echo "Publicación y comentarios asociados eliminados exitosamente.";
        } else {
            mysqli_rollback($conn);
            echo "Error al eliminar la publicación y/o sus comentarios.";
        }

        mysqli_stmt_close($stmt_eliminar_comentarios);
        mysqli_stmt_close($stmt_eliminar_publicacion);

        mysqli_close($conn);
    } else {
        echo "Error en la conexión a la base de datos.";
    }
} else {
    echo "ID de publicación no proporcionado.";
}
?>;
