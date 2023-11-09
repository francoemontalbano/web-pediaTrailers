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

    // Insertar la serie en la tabla de favoritos_series solo si no existe previamente
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "pelispedialogin";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Consultar si la serie ya está en favoritos_series
    $checkQuery = "SELECT id FROM favoritos_series WHERE id_usuario = ? AND id_serie_api = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("ii", $userId, $mediaId);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        // La serie ya está en favoritos_series, enviar una respuesta JSON de error
        echo json_encode(['success' => false, 'message' => 'La serie ya está en favoritos']);
    } else {
        // La serie no está en favoritos_series, insertarla
        $insertQuery = "INSERT INTO favoritos_series (id_usuario, id_serie_api) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ii", $userId, $mediaId);

        if ($stmt->execute()) {
            // Serie agregada a favoritos, enviar una respuesta JSON de éxito
            echo json_encode(['success' => true, 'message' => 'Serie agregada a Favoritos']);
        } else {
            // Error al agregar la serie a favoritos, enviar una respuesta JSON de error
            echo json_encode(['success' => false, 'message' => 'Error al agregar la serie a Favoritos']);
        }
        $stmt->close();
    }

    $checkStmt->close();
    $conn->close();
}
?>
