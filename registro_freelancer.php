<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a la base de datos
    $conn = new mysqli("localhost", "root", "", "innovatec");

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Recibir datos del formulario
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
    $especialidad = trim($_POST['especialidad']);
    $experiencia = trim($_POST['experiencia']);

    // Verificar si el email ya está registrado
    $stmt = $conn->prepare("SELECT id FROM freelancers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $mensaje = "<div class='error'>Error: El email ya está registrado.</div>";
    } else {
        // Insertar en la base de datos usando una consulta preparada
        $stmt = $conn->prepare("INSERT INTO freelancers (nombre, email, contraseña, especialidad, experiencia) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nombre, $email, $contraseña, $especialidad, $experiencia);

        if ($stmt->execute()) {
            $mensaje = "<div class='success'>Registro exitoso como Freelancer.</div>";
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
    <title>Registro Freelancer</title>
    <link rel="stylesheet" href="style_registros.css">
</head>
<body>
    <div class="form-container freelancer">
        <h2>Registro de Freelancer</h2>
        <?php if (isset($mensaje)) echo $mensaje; ?>
        <form action="registro_freelancer.php" method="POST">
            <label>Nombre:</label>
            <input type="text" name="nombre" required><br><br>

            <label>Email:</label>
            <input type="email" name="email" required><br><br>

            <label>Contraseña:</label>
            <input type="password" name="contraseña" required><br><br>

            <label>Especialidad:</label>
            <input type="text" name="especialidad" required><br><br>

            <label>Experiencia:</label>
            <textarea name="experiencia"></textarea><br><br>

            <button type="submit">Registrarse</button>
        </form>
    </div>
</body>
</html>
