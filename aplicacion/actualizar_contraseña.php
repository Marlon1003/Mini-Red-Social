<?php
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["token"], $_POST["nueva_contrasena"])) {
$token = $_POST["token"];
$nueva_contrasena = $_POST["nueva_contrasena"];

$sql = "SELECT id_usuario FROM tokens WHERE token = ? AND expira >= NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $id_usuario = $row["id_usuario"];

    $check_user_sql = "SELECT id FROM Usuarios WHERE id = ?";
    $check_user_stmt = $conn->prepare($check_user_sql);
    $check_user_stmt->bind_param("i", $id_usuario);
    $check_user_stmt->execute();
    $user_result = $check_user_stmt->get_result();

    if ($user_result->num_rows === 1) {
        $hashed_password = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
        $update_sql = "UPDATE Usuarios SET contraseña = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $hashed_password, $id_usuario);
        $update_result = $update_stmt->execute();

        if ($update_result) {
            $delete_sql = "DELETE FROM tokens WHERE token = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("s", $token);
            $delete_stmt->execute();

            echo "¡Contraseña actualizada con éxito! Serás redirigido al inicio de sesión en unos segundos.";

            header("refresh:5; url=login.php");
            exit();
        } else {
            echo "Error al actualizar la contraseña. Por favor, inténtalo de nuevo.";
        }
    } else {
        echo "El id de usuario no existe en la tabla Usuarios.";
    }
} else {
    echo "Token inválido o expirado.";
}}
