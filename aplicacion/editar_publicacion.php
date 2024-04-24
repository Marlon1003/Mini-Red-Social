<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["id_publicacion"]) && isset($_POST["nuevo_contenido"])) {
        include_once("db_connection.php");

        $id_publicacion = $_POST["id_publicacion"];
        $nuevo_contenido = $_POST["nuevo_contenido"];

        $sql = "UPDATE publicaciones SET contenido = ?, fecha_actualizacion = NOW() WHERE id = ?";

        if ($conn) {
            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "si", $nuevo_contenido, $id_publicacion);

                if (mysqli_stmt_execute($stmt)) {
                    echo "La publicación se actualizó correctamente.";
                } else {
                    echo "Error al actualizar la publicación.";
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
        echo "Error: Se requieren todos los datos necesarios para actualizar la publicación.";
    }
} else {
    echo "Error: Esta página solo se puede acceder mediante una solicitud POST.";
}
?>;
