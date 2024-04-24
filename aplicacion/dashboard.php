<?php
session_start();

if (!isset($_SESSION["id"])) {
   
    header("Location: login.php");
    exit; 
}

$id_usuario_actual = $_SESSION["id"];

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
}

$sql_publicaciones = "SELECT p.*, u.nombre_usuario FROM publicaciones p INNER JOIN usuarios u ON p.id_usuario = u.id ORDER BY p.fecha_creacion DESC";
$resultado_publicaciones = mysqli_query($conn, $sql_publicaciones);


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <span style="font-family: verdana, geneva, sans-serif">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles_2.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
</head>
<body>
    <div class="container">
    <nav>
        <ul>
            <li><a href="#" class="logo">
                <img src="img/spy.png" alt="">
                <span class="nav-item">ADMIN</span>
            </a></li>
            <li><a href="#">
            <i class="fas fa-menorah"></i>
            <span class="nav-item">Dashboard</span>
            </a></li>
            <li><a href="#">
            <i class="fas fa-comment"></i>
            <span class="nav-item">Mensajes</span>
            </a></li>
            <li><a href="#">
            <i class="fas fa-database"></i>
            <span class="nav-item">Soporte</span>
            </a></li>
            <li><a href="#">
            <i class="fas fa-chart-bar"></i>
            <span class="nav-item">Informe</span>
            </a></li>
            <li><a href="eliminar_cuenta.php" onclick="confirmarEliminarCuenta()" class="delete">
            <i class="fas fa-user-slash"></i>
            <span class="nav-item">Eliminar Cuenta</span>
            </a></li>
            <li><a href="cerrarSesion.php" class="logout">
            <i class="fas fa-sign-out-alt"></i>
            <span class="nav-item">Cerrar Sesión</span>
            </a></li> 
        </ul>
        </nav>
        <section class="main">
        <div class="main-top">
            <h1>DASHBOARD</h1>
            <i class="fas fa-user-check"></i>
        </div>
        <div class="users">
            <div class="card">
            <img src="img/cowboy.png">
            <h4>Ricardo Torres</h4>
            <p>programmer</p>
            <div class="per">
                <table>
                <tr>
                    <td><span>30%</span></td>
                    <td><span>70%</span></td>
                </tr>
                <tr>
                    <td>Online</td>
                    <td>Offline</td>
                </tr>
                </table>
            </div>
            <button>Perfil</button>
            </div>
            <div class="card">
            <img src="img/human.png">
            <h4>Martha Flores</h4>
            <p>Ux designer</p>
            <div class="per">
                <table>
                <tr>
                    <td><span>60%</span></td>
                    <td><span>40%</span></td>
                </tr>
                <tr>
                    <td>Online</td>
                    <td>Offline</td>
                </tr>
                </table>
            </div>
            <button>Perfil</button>
            </div>
            <div class="card">
            <img src="img/man_2.png">
            <h4>Alan Martínez</h4>
            <p>programmer</p>
            <div class="per">
                <table>
                <tr>
                    <td><span>20%</span></td>
                    <td><span>80%</span></td>
                </tr>
                <tr>
                    <td>Online</td>
                    <td>Offline</td>
                </tr>
                </table>
            </div>
            <button>Perfil</button>
            </div>
            <div class="card">
            <img src="img/woman.png">
            <h4>Mónica García</h4>
            <p>Ui designer</p>
            <div class="per">
                <table>
                <tr>
                    <td><span>10%</span></td>
                    <td><span>90%</span></td>
                </tr>
                <tr>
                    <td>Online</td>
                    <td>Offline</td>
                </tr>
                </table>
            </div>
            <button>Perfil</button>
            </div>
        </div>
        <div class="publicacion-form">
    <h2>Crear Publicación</h2>
    <form action="dashboard.php" method="post">
        <label for="contenido">Comparte tu estado de ánimo:</label><br><br>
        <textarea id="contenido" name="contenido" rows="7" cols="80" required></textarea><br><br>
        <input type="submit" value="Publicar"><br><br>
    </form>
</div>

<div class="publicaciones">
    <h2>Publicaciones</h2>
    <?php
    if (mysqli_num_rows($resultado_publicaciones) > 0) {
        while ($publicacion = mysqli_fetch_assoc($resultado_publicaciones)) {
            echo "<div class='publicacion'>";
            echo "<div class='comentario-box'>";
            echo "<p><strong>{$publicacion['nombre_usuario']}:</strong> {$publicacion['contenido']}</p>";
            echo "<p class='fecha-publicacion'><em>{$publicacion['fecha_creacion']}</em></p>";
            if ($publicacion['id_usuario'] == $id_usuario_actual) {
                echo "<button class='editar-btn' data-id='{$publicacion['id']}'>Editar</button>";
                echo "<button class='eliminar-btn' data-id='{$publicacion['id']}'>Eliminar</button>";
            }
            echo "<button class='responder-btn' data-id='{$publicacion['id']}'>Responder</button>";

            echo "<div class='comentario-form' id='comentario-form-{$publicacion['id']}' style='display: none;'>";
            echo "<h3>Responder a esta publicación</h3>";
            echo "<form action='guardar_comentario.php' method='post'>";
            echo "<input type='hidden' name='id_publicacion' value='{$publicacion['id']}'>";
            echo "<textarea name='contenido' rows='3' cols='50' placeholder='Escribe tu comentario aquí' required></textarea><br>";
            echo "<input type='submit' value='Enviar' class='boton-enviar'>";
            echo "</form>";
            echo "</div>"; 
            echo "</div>"; 


            $id_publicacion = $publicacion['id'];
            $sql_comentarios = "SELECT c.*, u.nombre_usuario FROM comentarios c INNER JOIN usuarios u ON c.id_usuario = u.id WHERE id_publicacion = ?";
            $stmt_comentarios = mysqli_prepare($conn, $sql_comentarios);
            mysqli_stmt_bind_param($stmt_comentarios, "i", $id_publicacion);
            mysqli_stmt_execute($stmt_comentarios);
            $resultado_comentarios = mysqli_stmt_get_result($stmt_comentarios);

            if (mysqli_num_rows($resultado_comentarios) > 0) {
                echo "<div class='comentarios'>";
                while ($comentario = mysqli_fetch_assoc($resultado_comentarios)) {
                    echo "<div class='comentario comentario-box'>";
                    echo "<p><strong>{$comentario['nombre_usuario']}:</strong> {$comentario['contenido']}</p>";
                    echo "<i class='fas fa-times close-icon' data-id='{$comentario['id']}'></i>";
                    echo "</div>";
                }
                echo "</div>"; 
            } else {
                echo "<div class='comentario comentario-box'>";
                echo "<p>No hay comentarios para mostrar.</p>";
                echo "</div>";
            } 
            
            echo "</div>";
        }
    } else {
        echo "<p>No hay publicaciones para mostrar.</p>";
    }
    ?>
</div>

                <script>
                    document.querySelectorAll('.responder-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const idPublicacion = this.getAttribute('data-id');
                            const formulario = document.getElementById(`comentario-form-${idPublicacion}`);
                            formulario.style.display = (formulario.style.display === 'none') ? 'block' : 'none';
                        });
                    });
                </script>


                <script>
                    document.querySelectorAll('.eliminar-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const idPublicacion = this.getAttribute('data-id');

                            if (confirm('¿Estás seguro de que quieres eliminar esta publicación?')) {
                                fetch('eliminar_publicacion.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded',
                                    },
                                    body: `id_publicacion=${idPublicacion}`,
                                })
                                .then(response => response.text())
                                .then(data => {
                                    alert(data);
                                    location.reload();
                                })
                                .catch(error => {
                                    console.error('Error al eliminar la publicación:', error);
                                    alert('Error al eliminar la publicación. Inténtalo de nuevo más tarde.');
                                });
                            }
                        });
                    });
                </script>


                <script>
                    function cancelarEdicion() {
                        location.reload();
                    }
                </script>

                <script>
                    document.querySelectorAll('.editar-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const idPublicacion = this.getAttribute('data-id');
                            const contenidoOriginal = this.parentElement.querySelector('p').textContent.trim();
                            
                            const formularioEdicion = document.createElement('form');
                            formularioEdicion.innerHTML = `
                                <textarea id='contenido-edit-${idPublicacion}' rows='4' cols='50'>${contenidoOriginal}</textarea><br><br>
                                <input type='button' value='Guardar Cambios' class='guardar-btn' onclick='actualizarPublicacion(${idPublicacion})'>
                                <input type='button' value='Cancelar' class='cancelar-btn' onclick='cancelarEdicion(${idPublicacion}, "${contenidoOriginal}")'>
                            `;
                            
                            const divComentario = this.parentElement;
                            divComentario.innerHTML = '';
                            divComentario.appendChild(formularioEdicion);
                        });
                    });
                </script>

                <script>
                    function actualizarPublicacion(idPublicacion) {
                        const nuevoContenido = document.getElementById(`contenido-edit-${idPublicacion}`).value;
                        const formData = new FormData();
                        formData.append('id_publicacion', idPublicacion);
                        formData.append('nuevo_contenido', nuevoContenido);

                        fetch('editar_publicacion.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.text())
                        .then(data => {
                            alert(data);
                            location.reload();
                        })
                        .catch(error => {
                            console.error('Error al actualizar la publicación:', error);
                            alert('Error al actualizar la publicación. Inténtalo de nuevo más tarde.');
                        });
                        }
                </script>

                <script>
                    function confirmarEliminarCuenta() {
                        if (confirm("¿Estás seguro de eliminar tu cuenta permanentemente?")) {
                            window.location.href = "eliminar_cuenta.php?confirmado=true";
                        }
                    }
                </script>

                <script>
                    document.querySelectorAll('.close-icon').forEach(icon => {
                        icon.addEventListener('click', function() {
                            const comentarioId = this.dataset.id;
                            console.log("ID del comentario:", comentarioId);       
                            const confirmar = confirm('¿Estás seguro de eliminar este comentario?');
                        
                            if (confirmar) {
                                eliminarComentario(comentarioId);
                            }
                        });
                    });

                    function eliminarComentario(comentarioId) {
                        const formData = new FormData();
                        formData.append('comentarioId', comentarioId);

                        fetch('eliminar_comentario.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => {
                            if (response.ok) {
                                location.reload();
                            } else {
                                console.error('Error al eliminar el comentario');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    }
                </script>
            </div>
        </section>
    </div>
</body>
</html>


