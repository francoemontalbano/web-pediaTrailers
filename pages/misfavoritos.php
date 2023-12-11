<?php
session_start();

// Verifico si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header('Location: login.php');
  exit;
}

// Verifico si existe la cookie de usuario invitado
if (isset($_COOKIE['usuario_invitado'])) {
  // Si es usuario invitado, mostrar el botón con el texto adecuado
  $cerrarSesionTexto = 'Cerrar sesión de Invitado e Iniciar sesión';
  $cerrarSesionEnlace = 'login.php';
} else {
  // Si no es usuario invitado, mostrar el botón con el texto estándar
  $cerrarSesionTexto = 'Cerrar Sesión';
  $cerrarSesionEnlace = 'cerrarsesion.php';
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


function getMediaDetails($mediaId, $mediaType)
{
  $apiKey = '5f4e3a08b9be2e852b443b4cb14f45f7';
  if ($mediaType === 'pelicula') {
    $mediaUrl = "https://api.themoviedb.org/3/movie/$mediaId?api_key=$apiKey&language=es-ES";
  } elseif ($mediaType === 'serie') {
    $mediaUrl = "https://api.themoviedb.org/3/tv/$mediaId?api_key=$apiKey&language=es-ES";
  }

  $mediaData = file_get_contents($mediaUrl);
  $mediaDetails = json_decode($mediaData, true);
  return $mediaDetails;
}
//Verifico si la solicitud al servidor es POST
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
    echo "Eliminado con éxito"; // 
    exit;
  } else {
    echo "Error al eliminar"; // 
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
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
          <a class="nav-link text-white" href="<?php echo $cerrarSesionEnlace; ?>" style="font-size: 16px;"><?php echo $cerrarSesionTexto; ?></a>
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

            // Obtengo los detalles de la película
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
          // Modifico para mostrar un mensaje diferente si el usuario es invitado
          if (strpos($userId, 'guest_') !== false) {
            echo '<tr>';
            echo '<td colspan="2" style="text-align: center; font-weight: bold; color: red;">Debe registrarse para utilizar esta función</td>';
            echo '</tr>';
          } else {
            echo '<tr>';
            echo '<td colspan="2">No tienes películas favoritas.</td>';
            echo '</tr>';
          }
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

            // Obtengo los detalles de la serie
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
          if (strpos($userId, 'guest_') !== false) {
            echo '<tr>';
            echo '<td colspan="2" style="text-align: center; font-weight: bold; color: red;">Debe registrarse para utilizar esta función</td>';
            echo '</tr>';
          } else {
            echo '<tr>';
            echo '<td colspan="2">No tienes series favoritas.</td>';
            echo '</tr>';
          }
        }

        // Función para obtener estadísticas de géneros para películas del usuario actual en porcentaje
        function obtenerEstadisticasGenerosPeliculas($userId, $conn)
        {
          $query = "SELECT genero, COUNT(*) as cantidad FROM favoritos WHERE id_usuario = ? GROUP BY genero";
          $stmt = $conn->prepare($query);
          $stmt->bind_param("s", $userId);
          $stmt->execute();
          $result = $stmt->get_result();

          $totalPeliculas = 0;

          while ($row = $result->fetch_assoc()) {
            $totalPeliculas += $row['cantidad'];
          }

          $estadisticasGeneros = [];

          // Calculo porcentajes redondeados
          $result->data_seek(0); // Reiniciar puntero del resultado
          while ($row = $result->fetch_assoc()) {
            $genero = $row['genero'];
            $cantidad = $row['cantidad'];
            $porcentaje = round(($cantidad / $totalPeliculas) * 100, 2) . "%";
            $estadisticasGeneros[$genero] = $porcentaje;
          }

          $stmt->close();

          return $estadisticasGeneros;
        }


        // Obtengo estadísticas de géneros de películas para el usuario actual
        $userId = $_SESSION['id_usuario'];
        $estadisticasPeliculas = obtenerEstadisticasGenerosPeliculas($userId, $conn);



        // Función para obtener estadísticas de géneros para series del usuario actual en porcentaje
        function obtenerEstadisticasGenerosSeries($userId, $conn)
        {
          $query = "SELECT genero, COUNT(*) as cantidad FROM favoritos_series WHERE id_usuario = ? GROUP BY genero";
          $stmt = $conn->prepare($query);
          $stmt->bind_param("s", $userId);
          $stmt->execute();
          $result = $stmt->get_result();

          $totalSeries = 0;

          while ($row = $result->fetch_assoc()) {
            $totalSeries += $row['cantidad'];
          }

          $estadisticasGeneros = [];

          // Calculo porcentajes redondeados
          $result->data_seek(0); 
          while ($row = $result->fetch_assoc()) {
            $genero = $row['genero'];
            $cantidad = $row['cantidad'];
            $porcentaje = round(($cantidad / $totalSeries) * 100, 2) . "%";
            $estadisticasGeneros[$genero] = $porcentaje;
          }

          $stmt->close();

          return $estadisticasGeneros;
        }

        // Obtengo estadísticas de géneros de series para el usuario actual
        $userId = $_SESSION['id_usuario'];
        $estadisticasSeries = obtenerEstadisticasGenerosSeries($userId, $conn);



        ?>
      </tbody>
    </table>
    <div class="container mt-5">
  <?php
  if (isset($_COOKIE['usuario_invitado'])) {
    echo '<h1>Porcentaje de Géneros en tus listas:</h1>';
    echo '<div class="alert alert-danger text-center mt-3" role="alert" style="font-weight: bold; color: red; background-color: white;">Debe registrarse para utilizar esta función</div>';
    echo '</div>';
  } else {
    echo '<div class="contenedor-graficos">';

    if (!empty($estadisticasPeliculas) || !empty($estadisticasSeries)) {
      echo '<h1>Porcentaje de Géneros en tus listas:</h1>';
    }

    if (!empty($estadisticasPeliculas)) {
      echo '<div class="grafico-container">';
      echo '<h2>Películas:</h2>';
      echo '<div id="estadisticasPeliculas"></div>';
      echo '</div>';
    }

    if (!empty($estadisticasSeries)) {
      echo '<div class="grafico-container">';
      echo '<h2>Series:</h2>';
      echo '<div id="estadisticasSeries"></div>';
      echo '</div>';
    }

    echo '</div>';
  }
  ?>
</div>


      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          <?php
          // Verifico si hay estadísticas de películas y muestra el gráfico correspondiente
          if (!empty($estadisticasPeliculas)) {
            echo 'mostrarGraficoPastel(' . json_encode($estadisticasPeliculas) . ', "estadisticasPeliculas", "Estadísticas de Géneros de Películas");';
          }

          // Verifico si hay estadísticas de series y muestra el gráfico correspondiente
          if (!empty($estadisticasSeries)) {
            echo 'mostrarGraficoPastel(' . json_encode($estadisticasSeries) . ', "estadisticasSeries", "Estadísticas de Géneros de Series");';
          }
          ?>
        });


        // Función para mostrar gráficos de pastel
        function mostrarGraficoPastel(estadisticas, contenedorId, titulo) {
          var contenedor = document.getElementById(contenedorId);
          var canvas = document.createElement('canvas');
          canvas.className = 'grafico-pastel';
          contenedor.appendChild(canvas);

          var ctx = canvas.getContext('2d');
          var generos = Object.keys(estadisticas);
          var porcentajes = Object.values(estadisticas).map(parseFloat); // Convertir cadenas a valores numéricos

          var data = {
            labels: generos,
            datasets: [{
              data: porcentajes,
              backgroundColor: [
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 206, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(153, 102, 255, 0.8)',
                'rgba(255, 159, 64, 0.8)',
              ],
            }],
          };

          var options = {
            title: {
              display: true,
              text: titulo,
            },
          };

          new Chart(ctx, {
            type: 'pie',
            data: data,
            options: options,
          });
        }


        function eliminarPelicula(movieId) {
          Swal.fire({
            title: '¿Estás seguro?',
            text: '¡La película será eliminada de tus favoritos!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
          }).then((result) => {
            if (result.isConfirmed) {
              realizarEliminacion(movieId, 'pelicula');
            }
          });
        }

        function eliminarSerie(seriesId) {
          Swal.fire({
            title: '¿Estás seguro?',
            text: '¡La serie será eliminada de tus favoritos!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
          }).then((result) => {
            if (result.isConfirmed) {
              realizarEliminacion(seriesId, 'serie');
            }
          });
        }

        function realizarEliminacion(mediaId, mediaType) {
          $.ajax({
            type: 'POST',
            url: 'misfavoritos.php',
            data: {
              mediaId: mediaId,
              mediaType: mediaType
            },
            success: function(data) {
              if (data === 'Eliminado con éxito') {
                Swal.fire({
                  title: '¡Eliminado!',
                  text: 'El elemento ha sido eliminado con éxito.',
                  icon: 'success'
                }).then(() => {
                  location.reload();
                });
              } else {
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