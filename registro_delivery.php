<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos
    $conn = new mysqli("localhost", "root", "", "innovatec");

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Obtener los datos del formulario
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
    $vehiculo = trim($_POST['vehiculo']);
    $disponibilidad = $_POST['disponibilidad'];

    // Verificar si el correo ya está registrado
    $stmt = $conn->prepare("SELECT id FROM delivery WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $mensaje = "<div class='error'>Error: El email ya está registrado.</div>";
    } else {
        // Insertar en la base de datos utilizando sentencias preparadas
        $stmt = $conn->prepare("INSERT INTO delivery (nombre, email, contraseña, vehiculo, disponibilidad) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nombre, $email, $contraseña, $vehiculo, $disponibilidad);

        if ($stmt->execute()) {
            $mensaje = "<div class='success'>Registro exitoso como Delivery.</div>";
        } else {
            $mensaje = "<div class='error'>Error al registrar: " . $conn->error . "</div>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro Delivery</title>
    <link rel="stylesheet" href="style_registros.css">
</head>
<body>
    <div class="form-container delivery">
        <h2>Registro de Repartidor</h2>
        <?php if (isset($mensaje)) echo $mensaje; ?>
        <form action="registro_delivery.php" method="POST">
            <label>Nombre:</label>
            <input type="text" name="nombre" required><br><br>

            <label>Email:</label>
            <input type="email" name="email" required><br><br>

            <label>Contraseña:</label>
            <input type="password" name="contraseña" required><br><br>

            <label>Vehículo:</label>
            <input type="text" name="vehiculo" required><br><br>

            <label>Disponibilidad:</label>
            <select name="disponibilidad">
                <option value="Disponible">Disponible</option>
                <option value="No Disponible">No Disponible</option>
            </select><br><br>

            <button type="submit">Registrarse</button>
        </form>
    </div>
</body>
</html>
