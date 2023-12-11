<?php
session_start();

// Verifico si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtengo el ID de la serie de la API desde la solicitud
    $mediaId = $_POST['mediaId'];
    // Obtengo el ID del usuario desde la sesión
    $userId = $_SESSION['id_usuario'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "pelispedialogin";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Obtengo información del género de la serie desde la API de themoviedb usando clients url
    $apiKey = '5f4e3a08b9be2e852b443b4cb14f45f7';
    $apiUrl = "https://api.themoviedb.org/3/tv/{$mediaId}?api_key={$apiKey}&language=es-ES";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $apiResponse = curl_exec($ch);
    curl_close($ch);

    $apiData = json_decode($apiResponse, true);

    // Verifico si la serie existe en la API
    if ($apiData && isset($apiData['genres']) && !empty($apiData['genres'])) {
        // Tomo el primer género de la lista (puedes ajustar esto según tus necesidades)
        $genre = $apiData['genres'][0]['name'];

        // Consulto si la serie ya está en favoritos_series
        $checkQuery = "SELECT id FROM favoritos_series WHERE id_usuario = ? AND id_serie_api = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("ii", $userId, $mediaId);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            // La serie ya está en favoritos_series, enviar una respuesta JSON de error
            echo json_encode(['success' => false, 'message' => 'La serie ya está en favoritos']);
        } else {
            // Verifico si el usuario actual es un usuario invitado
            if (strpos($userId, 'guest_') !== false) {
                echo json_encode(['success' => false, 'message' => 'Debe registrarse para usar esta función']);
                exit;
            }

            // La serie no está en favoritos_series, insertarla junto con el nombre del género
            $insertQuery = "INSERT INTO favoritos_series (id_usuario, id_serie_api, genero) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("iis", $userId, $mediaId, $genre);

            if ($stmt->execute()) {
                // Serie agregada a favoritos_series, enviar una respuesta JSON de éxito
                echo json_encode(['success' => true, 'message' => 'Serie agregada a Favoritos']);
            } else {
                // Error al agregar la serie a favoritos_series, enviar una respuesta JSON de error
                echo json_encode(['success' => false, 'message' => 'Error al agregar la serie a Favoritos']);
            }
            $stmt->close();
        }

        $checkStmt->close();
    } else {
        // No se pudo obtener información del género desde la API
        echo json_encode(['success' => false, 'message' => 'Error al obtener información del género desde la API']);
    }

    $conn->close();
}
?>
