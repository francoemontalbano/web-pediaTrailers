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

    // Insertar la película en la tabla de favoritos solo si no existe previamente
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "pelispedialogin";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Consultar si la película ya está en favoritos
    $checkQuery = "SELECT id FROM favoritos WHERE id_usuario = ? AND id_pelicula_api = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("ii", $userId, $mediaId);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        // La película ya está en favoritos, enviar una respuesta JSON de error
        echo json_encode(['success' => false, 'message' => 'La película ya está en favoritos']);
    } else {
        // Verificar si el usuario actual es un usuario invitado
        if (strpos($userId, 'guest_') !== false) {
            echo json_encode(['success' => false, 'message' => 'Debe registrarse para usar esta función']);
            exit;
        }

        // La película no está en favoritos, insertarla
        $insertQuery = "INSERT INTO favoritos (id_usuario, id_pelicula_api) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ii", $userId, $mediaId);

        if ($stmt->execute()) {
            // Película agregada a favoritos, enviar una respuesta JSON de éxito
            echo json_encode(['success' => true, 'message' => 'Película agregada a Favoritos']);
        } else {
            // Error al agregar la película a favoritos, enviar una respuesta JSON de error
            echo json_encode(['success' => false, 'message' => 'Error al agregar la película a Favoritos']);
        }
        $stmt->close();
    }

    $checkStmt->close();
    $conn->close();
}
?>
