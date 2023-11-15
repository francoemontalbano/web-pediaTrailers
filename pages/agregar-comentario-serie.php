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
    // Obtener el comentario desde la solicitud
    $commentText = $_POST['commentText'];
    // Obtener la fecha desde la solicitud
    $fecha = $_POST['fecha'];

    // Insertar el comentario en la tabla de comentarios_series
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "pelispedialogin";

    $conn = new mysqli($servername, $username, $password, $dbname);

    $insertQuery = "INSERT INTO comentarios_series (id_usuario, id_serie_api, comentario, fecha) VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("iiss", $userId, $mediaId, $commentText, $fecha);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Comentario de serie agregado con éxito']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al agregar el comentario de serie']);
    }

    $stmt->close();
    $conn->close();
}
?>
