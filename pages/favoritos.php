<?php
session_start();

// Verifico si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtengo el ID de la película de la API desde la solicitud
    $mediaId = $_POST['mediaId'];
    // Obtengo el ID del usuario desde la sesión
    $userId = $_SESSION['id_usuario'];

    // Inserto la película en la tabla de favoritos solo si no existe previamente
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "pelispedialogin";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Obtengo información del género de la película desde la API de themoviedb
    $apiKey = '5f4e3a08b9be2e852b443b4cb14f45f7';
    $apiUrl = "https://api.themoviedb.org/3/movie/{$mediaId}?api_key={$apiKey}&language=es-ES";

    // Utilizo cURL para la consulta a la API
    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $apiResponse = curl_exec($curl);

    if ($apiResponse === false) {
        // Error en la consulta API
        echo json_encode(['success' => false, 'message' => 'Error al obtener información del género desde la API']);
        exit;
    }

    $apiData = json_decode($apiResponse, true);

    // Verifico si la película existe en la API
    if ($apiData && isset($apiData['genres']) && !empty($apiData['genres'])) {
        // Tomo el primer género de la lista (puedes ajustar esto según tus necesidades)
        $genre = $apiData['genres'][0]['name'];
        
        // Consulto si la película ya está en favoritos
        $checkQuery = "SELECT id FROM favoritos WHERE id_usuario = ? AND id_pelicula_api = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("ii", $userId, $mediaId);
        $checkStmt->execute();
        $checkStmt->store_result();
        
        if ($checkStmt->num_rows > 0) {
            // La película ya está en favoritos, enviar una respuesta JSON de error
            echo json_encode(['success' => false, 'message' => 'La película ya está en favoritos']);
        } else {
            // Verifico si el usuario actual es un usuario invitado
            if (strpos($userId, 'guest_') !== false) {
                echo json_encode(['success' => false, 'message' => 'Debe registrarse para usar esta función']);
                exit;
            }
            
            // La película no está en favoritos, insertarla junto con el nombre del género
            $insertQuery = "INSERT INTO favoritos (id_usuario, id_pelicula_api, genero) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("iss", $userId, $mediaId, $genre);
            
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
    } else {
        // No se pudo obtener información del género desde la API
        echo json_encode(['success' => false, 'message' => 'Error al obtener información del género desde la API']);
    }

    curl_close($curl);
    $conn->close();
}
?>
