<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pelispedialogin";
$conn = new mysqli($servername, $username, $password, $dbname);

// Proceso el formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Valido los campos del formulario
  if (empty($username) || empty($password)) {
    $error_message = "Por favor complete todos los campos.";
  } elseif (strlen($password) < 6 || !preg_match("/[0-9]/", $password)) {
    $error_message = "La contraseña debe tener al menos 6 caracteres y al menos 1 número.";
  } elseif (strlen($username) < 6) {
    $error_message = "El nombre de usuario debe tener mínimo 6 caracteres.";
  } elseif (!preg_match("/^[a-zA-Z]+$/", $username)) {
    $error_message = "El nombre de usuario solo puede contener letras.";
  } else {
    // Verifico que el nombre de usuario no exista en la base de datos
    $check_user_query = "SELECT * FROM usuarios WHERE nombre_usuario=?";
    $stmt = $conn->prepare($check_user_query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user_result = $stmt->get_result();
    if ($user_result->num_rows > 0) {
      $error_message = "El nombre de usuario ya existe.";
    } else {
      // Inserto el nuevo usuario en la tabla "usuarios"
      $insert_user_query = "INSERT INTO usuarios (nombre_usuario) VALUES (?)";
      $stmt = $conn->prepare($insert_user_query);
      $stmt->bind_param("s", $username);
      if ($stmt->execute() === TRUE) {
        // Obtengo el ID del usuario recién creado
        $id_usuario = $stmt->insert_id;

        // Inserto la contraseña del usuario en la tabla "contraseñas"
        $insert_password_query = "INSERT INTO contraseñas (id_usuario, contraseña) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_password_query);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("is", $id_usuario, $hashed_password);
        if ($stmt->execute() === TRUE) {
          // Muestro el modal de registro exitoso
          echo "<script>
                  window.addEventListener('DOMContentLoaded', function() {
                    $('#successModal').modal('show');
                  });
                </script>";
        } else {
          $error_message = "Error al registrar al usuario.";
        }
      } else {
        $error_message = "Error al registrar al usuario.";
      }
    }
  }
}

// Muestro el formulario de registro
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/registro.css">
  <link rel="icon" href="../img/Icono.png">
  <title>Registrarse</title>
</head>
<body>
  <div class="container">
    <div class="caja-registro">
      <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <br><h1>Registro</h1><br>
        <?php if (isset($error_message)) { ?>
          <script>
            window.addEventListener('DOMContentLoaded', function() {
              $('#errorModal').modal('show');
            });
          </script>
        <?php } ?>

        Nombre de usuario: <input type="text" name="username" autofocus><br>
        Contraseña: <input type="password" name="password">
        <input type="submit" value="Registrarse"> <br>
        <br><p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>

        <!-- Modal de Bootstrap para mostrar el mensaje de error -->
        <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="errorModalLabel">Error de registro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p><?php echo $error_message; ?></p>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal de Bootstrap para mostrar el mensaje de registro exitoso -->
        <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Registro exitoso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p>Tu cuenta ha sido registrada exitosamente.</p>
              </div>
              <div class="modal-footer">
                <a href="login.php" class="btn btn-primary">Iniciar sesión</a>
              </div>
            </div>
          </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
      </form>
    </div>
  </div>
</body>
</html>
