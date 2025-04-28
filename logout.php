<?php
session_start();  // Iniciar sesión
session_unset();  // Eliminar todas las variables de sesión
session_destroy();  // Destruir la sesión

// Redirigir al inicio de sesión
header("Location: login.php");
exit;
?>
