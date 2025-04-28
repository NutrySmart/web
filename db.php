<?php
$host = "localhost";
$user = "root"; // Usuario por defecto de MySQL en XAMPP
$pass = ""; // Sin contraseña por defecto
$dbname = "innovatec";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
} else {
    echo "Conexión exitosa";
}
?>
