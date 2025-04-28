<?php
session_start(); // Iniciar sesión

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos
    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "innovatec";

    $conn = new mysqli($host, $user, $pass, $dbname);
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Obtener los datos del formulario
    $email = $_POST['email'];
    $contraseña = $_POST['contraseña'];

    // Buscar el usuario en la base de datos
    $sql = "SELECT * FROM clientes WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verificar la contraseña
        if (password_verify($contraseña, $row['contraseña'])) {
            // Iniciar sesión y redirigir
            $_SESSION['usuario'] = $row['nombre'];
            echo "Bienvenido, " . $_SESSION['usuario'] . "!<br><br>";
            // Mostrar enlace para cerrar sesión
            echo '<a href="logout.php">Cerrar sesión</a>';
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "No se encontró el usuario.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="style_login.css">
</head>
<body>

    <!-- Imágenes laterales -->
    <img src="img/nutricion2.jpeg" alt="Nutrición Izquierda" class="side-img left">
    <img src="img/nutricion3.jpeg" alt="Nutrición Derecha" class="side-img right">

    <div class="login-container">
    <img src="img/logo.png" alt="Nutrición Izquierda" class="login-logo">

        <h2>Formulario de Inicio de Sesión</h2>
        <form action="login.php" method="POST">
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="contraseña">Contraseña:</label>
            <input type="password" id="contraseña" name="contraseña" required>
            
            <button type="submit">Iniciar sesión</button>
        </form>
    </div>

</body>
</html>
