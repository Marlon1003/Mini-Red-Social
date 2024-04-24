<?php
session_start();
include_once("db_connection.php");
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["comentarioId"]) && isset($_SESSION["id"])) {
    $comentarioId = $_POST["comentarioId"];
    $usuarioId = $_SESSION["id"];

    $stmt = mysqli_prepare($conn, "SELECT c.id_usuario, p.id_usuario FROM comentarios c INNER JOIN publicaciones p ON c.id_publicacion = p.id WHERE c.id = ?");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(array("error" => "Error en la preparación de la consulta"));
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $comentarioId);
    if (!mysqli_stmt_execute($stmt)) {
        http_response_code(500);
        echo json_encode(array("error" => "Error al ejecutar la consulta"));
        exit();
    }
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) === 0) {
        http_response_code(404);
        echo json_encode(array("error" => "Comentario no encontrado"));
        exit();
    }
    mysqli_stmt_bind_result($stmt, $propietarioComentario, $propietarioPublicacion);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($propietarioComentario == $usuarioId || $propietarioPublicacion == $usuarioId) {

        $stmt_delete = mysqli_prepare($conn, "DELETE FROM comentarios WHERE id = ?");
        if (!$stmt_delete) {
            http_response_code(500);
            echo json_encode(array("error" => "Error en la preparación de la consulta de eliminación"));
            exit();
        }
        mysqli_stmt_bind_param($stmt_delete, "i", $comentarioId);
        if (!mysqli_stmt_execute($stmt_delete)) {
            http_response_code(500);
            echo json_encode(array("error" => "Error al ejecutar la consulta de eliminación"));
            exit();
        }
        mysqli_stmt_close($stmt_delete);

        echo json_encode(array("message" => "El comentario fue eliminado correctamente"));
    } else {
        http_response_code(403);
        echo json_encode(array("error" => "No tienes permiso para eliminar este comentario"));
    }
} else {
    http_response_code(400);
    echo json_encode(array("error" => "Solicitud incorrecta"));
}
?>;
