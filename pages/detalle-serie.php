<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header('Location: login.php');
  exit;
}
?>


<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Detalle de la serie</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/estilos-detalle.css">
  <link rel="stylesheet" href="../css/comentarios.css">
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

  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <h1 id="series-title"></h1>
        <form id="favoriteForm" method="POST">
          <button id="addToFavorites" type="button">Agregar a Favoritos</button>
          <button id="removeFromFavorites" type="button" style="display: none">Eliminar de Favoritos</button>
        </form>
      </div>
      <div class="col-md-8" id="series-trailer-container"></div>
    </div>
  </div>



  <div class="container section">
    <div class="row">
      <div class="col-md-12">
        <h2>Plataformas:</h2>
        <p id="series-platforms"></p>
        <div id="no-series-platforms-message" style="display: none;">No se encontraron plataformas de transmisión.</div>
      </div>
    </div>
  </div>

  <div class="container section">
    <div class="row">
      <div class="col-md-12">
        <h2>Género:</h2>
        <div id="series-genres"></div>
        <p id="series-duration"></p>
      </div>
    </div>
  </div>

  <div class="container section">
    <div class="row">
      <div class="col-md-12">
        <h2>Sinopsis:</h2>
        <p id="series-overview"></p>
      </div>
    </div>
  </div>

  <div class="container section">
    <div class="row">
      <div class="col-md-12">
        <h2>Reparto:</h2>
        <div id="series-cast" class="row"></div>
      </div>
    </div>
  </div>

  <div class="container section">
    <div class="row">
      <div class="col-md-12">
        <h2>Imágenes:</h2>
        <div id="series-images" class="row"></div>
      </div>
    </div>
  </div>

  <div class="container section">
    <div class="row">
      <div class="col-md-12">
        <h2>Comentarios:</h2>
        <div id="comments-container"></div>
        <form id="commentForm">
          <textarea id="commentText" placeholder="Añade un comentario..." rows="4" required></textarea>
          <button type="submit">Comentar</button>
        </form>
      </div>
    </div>
  </div>

  <script src="detalle-serie.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</body>

</html>