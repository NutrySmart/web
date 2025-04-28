<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos
    $conn = new mysqli("localhost", "root", "", "innovatec");

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Recibir y limpiar datos del formulario
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);  // Encriptar contraseña

    // Verificar si el email ya existe en la base de datos
    $stmt = $conn->prepare("SELECT id FROM clientes WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $mensaje = "<div class='error'>Error: El email ya está registrado.</div>";
    } else {
        // Insertar datos de forma segura con sentencias preparadas
        $stmt = $conn->prepare("INSERT INTO clientes (nombre, email, contraseña) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $email, $contraseña);

        if ($stmt->execute()) {
            $mensaje = "<div class='success'>Registro exitoso como Cliente.</div>";
        } else {
            $mensaje = "<div class='error'>Error en el registro.</div>";
        }
    }

    // Cerrar conexiones
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="style.css">  <!-- Enlace a la hoja de estilos -->
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Formulario de Registro</h2>
            <?php if (isset($mensaje)) echo $mensaje; ?>
            <form action="registro.php" method="POST">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>

                <label for="contraseña">Contraseña:</label>
                <input type="password" id="contraseña" name="contraseña" required>

                <button type="submit">Registrar</button>
            </form>
        </div>
    </div>
</body>
</html>
