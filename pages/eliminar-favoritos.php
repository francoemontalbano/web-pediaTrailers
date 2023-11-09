<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener el ID de la película de la API desde la solicitud
    $mediaId = $_POST['mediaId'];
    // Obtener el ID del usuario desde la sesión
    $userId = $_SESSION['id_usuario'];

    // Conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "pelispedialogin";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Eliminar la película de favoritos
    $deleteQuery = "DELETE FROM favoritos WHERE id_usuario = ? AND id_pelicula_api = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("ii", $userId, $mediaId);

    if ($deleteStmt->execute()) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Película eliminada de Favoritos']);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Error al eliminar la película de Favoritos']);
    }
    $deleteStmt->close();

    $conn->close();
}
?>
