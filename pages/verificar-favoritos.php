<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['isInFavorites' => false]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtengo el ID de la película de la API desde la solicitud
    $mediaId = $_POST['mediaId'];
    // Obtengo el ID del usuario desde la sesión
    $userId = $_SESSION['id_usuario'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "pelispedialogin";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Consulto si la película ya está en favoritos
    $checkQuery = "SELECT id FROM favoritos WHERE id_usuario = ? AND id_pelicula_api = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("ii", $userId, $mediaId);
    $checkStmt->execute();
    $checkStmt->store_result();

    $isInFavorites = ($checkStmt->num_rows > 0);

    echo json_encode(['isInFavorites' => $isInFavorites]);

    $checkStmt->close();
    $conn->close();
}
?>
