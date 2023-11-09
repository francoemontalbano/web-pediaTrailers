<?php
// Conectar a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pelispedialogin";
$conn = new mysqli($servername, $username, $password, $dbname);

// Procesar el formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validar los campos del formulario
  if (empty($username) || empty($password)) {
    $error_message = "Por favor complete todos los campos.";
  } else {
    // Buscar el nombre de usuario en la base de datos
    $check_user_query = "SELECT usuarios.id, contraseñas.contraseña FROM usuarios INNER JOIN contraseñas ON usuarios.id = contraseñas.id_usuario WHERE usuarios.nombre_usuario=?";
    $stmt = $conn->prepare($check_user_query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user_result = $stmt->get_result();
    if ($user_result->num_rows == 0) {
      $error_message = "El nombre de usuario no existe.";
    } else {
      // Verificar la contraseña del usuario
      $user_row = $user_result->fetch_assoc();
      if (password_verify($password, $user_row["contraseña"])) {
        // Iniciar sesión y redirigir al inicio
        session_start();
        $_SESSION["id_usuario"] = $user_row["id"];
        $_SESSION["loggedin"] = true; // Variable para verificar inicio de sesión
        header("Location: index.php");
        exit();
      } else {
        $error_message = "La contraseña es incorrecta.";
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <link rel="stylesheet" href="../css/login.css">
  <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="icon" href="../img/Icono.png">
</head>
<body>
  <div class="container">
    <div class="caja-registro">
      <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <h1>Iniciar sesión</h1>
        <?php if (isset($error_message)) { ?>
          <script>
            window.addEventListener('DOMContentLoaded', function() {
              $('#errorModal').modal('show');
            });
          </script>
        <?php } ?>
        Nombre de usuario: <input type="text" name="username"><br>
        Contraseña: <input type="password" name="password"><br>
        <input type="submit" value="Aceptar"><br>
        <br><p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
      </form>
    </div>
  </div>

  <!-- Modal de Bootstrap para mostrar el mensaje de error -->
  <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="errorModalLabel">Error de inicio de sesión</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <?php echo $error_message; ?>
        </div>
      </div>
    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

