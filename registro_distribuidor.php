<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a la base de datos
    $conn = new mysqli("localhost", "root", "", "innovatec");

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Recibir y limpiar los datos del formulario
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
    $empresa = trim($_POST['empresa']);
    $productos = trim($_POST['productos']);

    // Verificar si el email ya está registrado
    $stmt = $conn->prepare("SELECT id FROM distribuidores WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $mensaje = "<div class='error'>Error: El email ya está registrado.</div>";
    } else {
        // Insertar los datos en la base de datos con sentencias preparadas
        $stmt = $conn->prepare("INSERT INTO distribuidores (nombre, email, contraseña, empresa, productos) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nombre, $email, $contraseña, $empresa, $productos);

        if ($stmt->execute()) {
            $mensaje = "<div class='success'>Registro exitoso como Distribuidor.</div>";
        } else {
            $mensaje = "<div class='error'>Error al registrar: " . $conn->error . "</div>";
        }
    }

    // Cerrar conexión
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro Distribuidor</title>
    <link rel="stylesheet" href="style_registros.css">
</head>
<body>
    <div class="form-container distribuidor">
        <h2>Registro de Distribuidor</h2>
        <?php if (isset($mensaje)) echo $mensaje; ?>
        <form action="registro_distribuidor.php" method="POST">
            <label>Nombre:</label>
            <input type="text" name="nombre" required><br><br>

            <label>Email:</label>
            <input type="email" name="email" required><br><br>

            <label>Contraseña:</label>
            <input type="password" name="contraseña" required><br><br>

            <label>Nombre de la Empresa:</label>
            <input type="text" name="empresa" required><br><br>

            <label>Productos que distribuye:</label>
            <textarea name="productos" required></textarea><br><br>

            <button type="submit">Registrarse</button>
        </form>
    </div>
</body>
</html>
