<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header('Location: login.php');
  exit;
}

$userId = $_SESSION['id_usuario'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pelispedialogin";

$apiKey = '5f4e3a08b9be2e852b443b4cb14f45f7';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("La conexión a la base de datos falló: " . $conn->connect_error);
}

// Función para obtener detalles de películas y series de forma asíncrona
function getMediaDetails($mediaId, $mediaType)
{
  $apiKey = '5f4e3a08b9be2e852b443b4cb14f45f7'; // Reemplaza con tu API key de themoviedb
  if ($mediaType === 'pelicula') {
    $mediaUrl = "https://api.themoviedb.org/3/movie/$mediaId?api_key=$apiKey&language=es-ES";
  } elseif ($mediaType === 'serie') {
    $mediaUrl = "https://api.themoviedb.org/3/tv/$mediaId?api_key=$apiKey&language=es-ES";
  }

  $mediaData = file_get_contents($mediaUrl);
  $mediaDetails = json_decode($mediaData, true);
  return $mediaDetails;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['mediaId']) && isset($_POST['mediaType'])) {
  $mediaId = $_POST['mediaId'];
  $mediaType = $_POST['mediaType'];

  if ($mediaType === 'pelicula') {
    $query = "DELETE FROM favoritos WHERE id_usuario = ? AND id_pelicula_api = ?";
  } elseif ($mediaType === 'serie') {
    $query = "DELETE FROM favoritos_series WHERE id_usuario = ? AND id_serie_api = ?";
  }

  $stmt = $conn->prepare($query);
  $stmt->bind_param("ii", $userId, $mediaId);

  if ($stmt->execute()) {
    echo "Eliminado con éxito"; // Puedes cambiar este mensaje de respuesta si lo deseas
    exit;
  } else {
    echo "Error al eliminar"; // Puedes cambiar este mensaje de respuesta si lo deseas
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mis Favoritos</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <link rel="stylesheet" href="../css/favoritos.css">
  <link rel="icon" href="../img/Icono.png">

</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-custom fixed-top">
    <a class="navbar-brand text-white" href="index.php">
      <img src="../img/Icono.png" alt="PediaTrailers Icon" width="30" height="30" class="mr-2">
      pediaTrailers
    </a> <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-white mr-2" id="btn-trailers-populares" href="index.php" style="font-size: 16px;">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white mr-2" href="proximamente-pelicula.php" style="font-size: 16px;">Top Estrenos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white mr-2" href="ranking.php" style="font-size: 16px;">Ranking</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white mr-2" id="btn-buscar-generos" href="generos.php" style="font-size: 16px;">Buscar por Géneros</a>
        </li>
      </ul>
      <form class="form-inline" action="resultados.php" method="GET">
        <input class="form-control mr-sm-2" type="search" placeholder="Buscar películas" aria-label="Search" name="searchTerm" required>
        <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Buscar</button>
      </form>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link text-white mr-2" href="misfavoritos.php" style="font-size: 16px;">Mis Favoritos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="cerrarsesion.php" style="font-size: 16px;">Cerrar Sesión</a>
        </li>
      </ul>
    </div>
  </nav>

  <div class="container mt-5">
    <h1>Películas Favoritas</h1>
    <table class="table">
      <thead>
        <tr>
          <th>Título</th>
          <th>Acción</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $query = "SELECT id_pelicula_api FROM favoritos WHERE id_usuario = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $movieId = $row['id_pelicula_api'];

            // Obtener los detalles de la película de forma asíncrona utilizando JavaScript
            echo '<tr>';
            echo '<td id="movieTitle_' . $movieId . '">Cargando...</td>';
            echo '<td>';
            echo '<button class="btn btn-delete-favorite" onclick="eliminarPelicula(' . $movieId . ')">Eliminar de Favoritos</button>';
            echo '</td>';
            echo '</tr>';
            echo '<script>';
            echo 'fetch("https://api.themoviedb.org/3/movie/' . $movieId . '?api_key=' . $apiKey . '&language=es-ES")';
            echo '.then(response => response.json())';
            echo '.then(data => {';
            echo 'document.getElementById("movieTitle_' . $movieId . '").textContent = data.title;';
            echo '})';
            echo '</script>';
          }
        } else {
          echo '<tr>';
          echo '<td colspan="2">No tienes películas favoritas.</td>';
          echo '</tr>';
        }
        ?>
      </tbody>
    </table>

    <h1>Series Favoritas</h1>
    <table class="table">
      <thead>
        <tr>
          <th>Título</th>
          <th>Acción</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $query = "SELECT id_serie_api FROM favoritos_series WHERE id_usuario = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $seriesId = $row['id_serie_api'];

            // Obtener los detalles de la serie de forma asíncrona utilizando JavaScript
            echo '<tr>';
            echo '<td id="seriesTitle_' . $seriesId . '">Cargando...</td>';
            echo '<td>';
            echo '<button class="btn btn-delete-favorite" onclick="eliminarSerie(' . $seriesId . ')">Eliminar de Favoritos</button>';
            echo '</td>';
            echo '</tr>';
            echo '<script>';
            echo 'fetch("https://api.themoviedb.org/3/tv/' . $seriesId . '?api_key=' . $apiKey . '&language=es-ES")';
            echo '.then(response => response.json())';
            echo '.then(data => {';
            echo 'document.getElementById("seriesTitle_' . $seriesId . '").textContent = data.name;';
            echo '})';
            echo '</script>';
          }
        } else {
          echo '<tr>';
          echo '<td colspan="2">No tienes series favoritas.</td>';
          echo '</tr>';
        }
        ?>
      </tbody>
    </table>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    function eliminarPelicula(movieId) {
      // Mostrar SweetAlert de confirmación
      Swal.fire({
        title: '¿Estás seguro?',
        text: '¡La película será eliminada de tus favoritos!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar' // Aquí especificamos el texto para el botón de cancelar
      }).then((result) => {
        if (result.isConfirmed) {
          // Si el usuario confirma, realiza la eliminación
          realizarEliminacion(movieId, 'pelicula');
        }
      });
    }

    function eliminarSerie(seriesId) {
      // Mostrar SweetAlert de confirmación
      Swal.fire({
        title: '¿Estás seguro?',
        text: '¡La serie será eliminada de tus favoritos!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar' // Aquí especificamos el texto para el botón de cancelar
      }).then((result) => {
        if (result.isConfirmed) {
          // Si el usuario confirma, realiza la eliminación
          realizarEliminacion(seriesId, 'serie');
        }
      });
    }

    function realizarEliminacion(mediaId, mediaType) {
      // Realizar la eliminación utilizando AJAX
      $.ajax({
        type: 'POST',
        url: 'misfavoritos.php',
        data: {
          mediaId: mediaId,
          mediaType: mediaType
        },
        success: function(data) {
          if (data === 'Eliminado con éxito') {
            // Mostrar SweetAlert de éxito
            Swal.fire({
              title: '¡Eliminado!',
              text: 'El elemento ha sido eliminado con éxito.',
              icon: 'success'
            }).then(() => {
              // Actualizar la tabla después de eliminar
              location.reload();
            });
          } else {
            // Mostrar SweetAlert de error
            Swal.fire({
              title: 'Error',
              text: 'Hubo un error al eliminar el elemento. Por favor, inténtalo de nuevo.',
              icon: 'error'
            });
          }
        }
      });
    }
  </script>
</body>

</html>