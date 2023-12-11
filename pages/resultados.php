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
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Resultados</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/resultados.css">
  <link rel="icon" href="../img/Icono.png">
  <script src="detalle-pelicula.js"></script>
  <script src="detalle-serie.js"></script>
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
</body>
</html>
<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Verifico si se ha enviado un término de búsqueda
    if (isset($_GET["searchTerm"])) {
        $searchTerm = $_GET["searchTerm"];

        // Verifico si el término de búsqueda no está vacío
        if (!empty($searchTerm)) {
            $apiKey = "5f4e3a08b9be2e852b443b4cb14f45f7";
            $language = "es"; // Establece el lenguaje en español
            $url = "https://api.themoviedb.org/3/search/multi?api_key=" . $apiKey . "&query=" . urlencode($searchTerm) . "&language=" . $language;

            // Realizo la solicitud a la API de TMDb
            $response = @file_get_contents($url); 

            if ($response !== false) {
                $data = json_decode($response, true);

                // Verifico si se encontraron resultados
                if (!empty($data["results"])) {
                    echo "<div class='cartelera'>";
                    foreach ($data["results"] as $result) {
                        $title = $result["title"] ?? $result["name"];
                        $mediaType = $result["media_type"] ?? $result["media_type"];
                        $posterPath = $result["poster_path"] ?? '';

                        if (!empty($posterPath)) {
                            echo "<div class='pelicula'>";
                            if ($mediaType == 'movie') {
                              echo "<a href='detalle-pelicula.php?id=" . $result["id"] . "'>";
                            } else {
                              echo "<a href='detalle-serie.php?id=" . $result["id"] . "'>";
                            }
                            
                            echo "<img src='https://image.tmdb.org/t/p/w200" . $posterPath . "' alt='" . $title . "' />";
                            echo "</a>";
                            echo "<h3>" . $title . "</h3>";
                            echo "</div>";
                        }
                    }
                    echo "</div>";
                } else {
                    // Muestro mensaje de error si no se encontraron resultados
                    echo "<div class='error-message'>";
                    echo "No se encontraron películas o series para: " . htmlspecialchars($searchTerm);
                    echo "<br>";
                    echo "Intentalo nuevamente.";
                    echo "</div>";
                }

                exit(); 
            }
        }
    }
}
?>

