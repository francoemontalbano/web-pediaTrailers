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
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Buscar por Géneros</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/generos.css">
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

  
  <div class="container my-4">
    <br><br><br><br><br>
    <h1 class="text-center mb-4">Buscar por Géneros</h1>
    <div class="row">
      <!-- Botón para filtrar películas por género -->
      <button id="show-movies-button" class="btn btn-primary">Filtrar por películas</button>

      <!-- Botón para filtrar series por género -->
      <button id="show-series-button" class="btn btn-primary">Filtrar por series</button>
    </div>

    <!-- Contenedor de botones de género para películas -->
    <div class="row mt-4" id="genre-buttons-movies">
      <h2>Películas:</h2>
      <!-- Aquí se crearán los botones de género para películas -->
    </div>
    <div class="row mt-4" id="movie-list">
      <!-- Aquí se mostrarán las películas recomendadas por género -->
    </div>
  </div>

  <div class="container my-4">
    <!-- Contenedor de botones de género para series -->
    <div class="row mt-4" id="genre-buttons-series">
      <h2>Series:</h2>
      <!-- Aquí se crearán los botones de género para series -->
    </div>
    <div class="row mt-4" id="series-list">
      <!-- Aquí se mostrarán las series recomendadas por género -->
    </div>
  </div>



  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="generos.js"></script>
</body>
</html>