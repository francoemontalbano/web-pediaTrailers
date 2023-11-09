<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener el ID de la serie de la API desde la solicitud
    $mediaId = $_POST['mediaId'];
    // Obtener el ID del usuario desde la sesión
    $userId = $_SESSION['id_usuario'];

    // Eliminar la serie de la tabla de favoritos_series si existe
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "pelispedialogin";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Consultar si la serie está en favoritos_series
    $checkQuery = "SELECT id FROM favoritos_series WHERE id_usuario = ? AND id_serie_api = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("ii", $userId, $mediaId);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        // La serie está en favoritos_series, eliminarla
        $deleteQuery = "DELETE FROM favoritos_series WHERE id_usuario = ? AND id_serie_api = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("ii", $userId, $mediaId);

        if ($deleteStmt->execute()) {
            // Serie eliminada de favoritos_series, enviar una respuesta JSON de éxito
            echo json_encode(['success' => true, 'message' => 'Serie eliminada de Favoritos']);
        } else {
            // Error al eliminar la serie de favoritos_series, enviar una respuesta JSON de error
            echo json_encode(['success' => false, 'message' => 'Error al eliminar la serie de Favoritos']);
        }
        $deleteStmt->close();
    } else {
        // La serie no está en favoritos_series, enviar una respuesta JSON de error
        echo json_encode(['success' => false, 'message' => 'La serie no está en favoritos']);
    }

    $checkStmt->close();
    $conn->close();
}
?>
