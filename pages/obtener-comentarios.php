<?php
$mediaId = $_GET['mediaId'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pelispedialogin";

$conn = new mysqli($servername, $username, $password, $dbname);



#$selectQueryPeliculas = "SELECT comentarios_peliculas.id, comentarios_peliculas.id_usuario, comentarios_peliculas.comentario, comentarios_peliculas.fecha FROM comentarios_peliculas LEFT JOIN usuarios ON comentarios_peliculas.id_usuario = usuarios.id WHERE comentarios_peliculas.id_pelicula_api = ?";
$selectQueryPeliculas = "SELECT cp.id, u.nombre_usuario as nombre_usuario, cp.fecha, cp.comentario FROM comentarios_peliculas cp JOIN usuarios u ON cp.id_usuario = u.id WHERE cp.id_pelicula_api = ?";
$selectQuerySeries = "SELECT comentarios_series.id, comentarios_series.id_usuario, comentarios_series.comentario, comentarios_series.fecha, usuarios.nombre_usuario FROM comentarios_series LEFT JOIN usuarios ON comentarios_series.id_usuario = usuarios.id WHERE comentarios_series.id_serie_api = ?";

// Obtener comentarios de películas
$stmtPeliculas = $conn->prepare($selectQueryPeliculas);
$stmtPeliculas->bind_param("i", $mediaId);
$stmtPeliculas->execute();
$resultPeliculas = $stmtPeliculas->get_result();
$commentsPeliculas = $resultPeliculas->fetch_all(MYSQLI_ASSOC);

// Obtener comentarios de series
$stmtSeries = $conn->prepare($selectQuerySeries);
$stmtSeries->bind_param("i", $mediaId);
$stmtSeries->execute();
$resultSeries = $stmtSeries->get_result();
$commentsSeries = $resultSeries->fetch_all(MYSQLI_ASSOC);

// Fusionar comentarios de películas y series
$comments = array_merge($commentsPeliculas, $commentsSeries);

echo json_encode($comments);

$stmtPeliculas->close();
$stmtSeries->close();
$conn->close();
?>
