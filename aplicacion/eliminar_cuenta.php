<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit; 
}

if (isset($_GET["confirmado"]) && $_GET["confirmado"] === "true") {
    include_once("db_connection.php");

    $id_usuario = $_SESSION["id"];

    $sql_eliminar_comentarios = "DELETE FROM comentarios WHERE id_usuario = ?";
    $stmt_eliminar_comentarios = $conn->prepare($sql_eliminar_comentarios);
    $stmt_eliminar_comentarios->bind_param("i", $id_usuario);
    $stmt_eliminar_comentarios->execute();

    $sql_eliminar_publicaciones = "DELETE FROM publicaciones WHERE id_usuario = ?";
    $stmt_eliminar_publicaciones = $conn->prepare($sql_eliminar_publicaciones);
    $stmt_eliminar_publicaciones->bind_param("i", $id_usuario);
    $stmt_eliminar_publicaciones->execute();

    $sql_eliminar_tokens = "DELETE FROM tokens WHERE id_usuario = ?";
    $stmt_eliminar_tokens = $conn->prepare($sql_eliminar_tokens);
    $stmt_eliminar_tokens->bind_param("i", $id_usuario);
    $stmt_eliminar_tokens->execute();

    $sql_eliminar_usuario = "DELETE FROM usuarios WHERE id = ?";
    $stmt_eliminar_usuario = $conn->prepare($sql_eliminar_usuario);
    $stmt_eliminar_usuario->bind_param("i", $id_usuario);
    $stmt_eliminar_usuario->execute();

    session_unset();
    session_destroy();

    header("Location: login.php");
    exit;
} else {
    header("Location: dashboard.php");
    exit;
}
