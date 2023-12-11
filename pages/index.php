<?php
session_start();

// Verifico si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header('Location: login.php');
  exit;
}

$id_usuario = $_SESSION['id_usuario'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pelispedialogin";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Error de conexión: " . $conn->connect_error);
}

$consulta_nombre_usuario = "SELECT nombre_usuario FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($consulta_nombre_usuario);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stmt->bind_result($nombre_usuario);
$stmt->fetch();
$stmt->close();

// Verifico si el usuario actual es un usuario invitado
if (strpos($nombre_usuario, 'guest_') !== false) {
  $nombre_usuario = 'Invitado';
} elseif (empty($nombre_usuario)) {
  // Si el nombre de usuario está vacío, asignar 'Invitado'
  $nombre_usuario = 'invitado';
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PediaTrailers</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
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
        <?php
        if ($nombre_usuario === 'invitado') {
          echo '<li class="nav-item">';
          echo '<a class="nav-link text-white" href="cerrarsesion.php" style="font-size: 16px;">Cerrar sesión de invitado e Iniciar sesión</a>';
          echo '</li>';
        } else {
          echo '<li class="nav-item">';
          echo '<a class="nav-link text-white" href="cerrarsesion.php" style="font-size: 16px;">Cerrar Sesión</a>';
          echo '</li>';
        }
        ?>
      </ul>
    </div>
  </nav>

  <div class="container my-4">
    <br><br>
    <h1 class="bienvenida-texto">¡Que alegría verte por aquí, <strong><?php echo $nombre_usuario; ?></strong>!</h1>
    <h1 class="text-center mb-4">Te recomendamos...</h1><br><br>
    <div id="movie-carousel" class="carousel slide" data-ride="carousel">
      <div class="carousel-inner" id="movie-posters">

      </div>
    </div>
    <a class="carousel-control-prev" href="#movie-carousel" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#movie-carousel" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
  </div>




  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="carousel.js"></script>
  <footer class="bg-custom text-center text-white mt-4 p-3">
    <img src="../img/Icono.png" width="30" height="30" class="mr-2">
    pediaTrailers &copy; <?php echo date("Y"); ?>
  </footer>
</body>

</html>