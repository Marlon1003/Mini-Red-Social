<?php
include 'db_connection.php';

if ($conn) {
    echo "Conexión exitosa a la base de datos.";
} else {
    echo "Error al conectar a la base de datos: " . mysqli_connect_error();
}
?>;

