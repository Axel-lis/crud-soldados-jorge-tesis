<?php 
// Iniciar sesión en PHP
session_start();

// Establecer el tiempo de expiración de la sesión en minutos
$timeout_duration = 10;

// Comprobar si la sesión está activa
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $timeout_duration * 60)) {
    // Si ha pasado el tiempo de inactividad, destruir la sesión
    session_unset();     // Libera todas las variables de sesión
    session_destroy();   // Destruye la sesión
}

// Actualizar la última actividad
$_SESSION['LAST_ACTIVITY'] = time();

// Incluir el archivo de conexión a la base de datos
include('scripts/conexion.php');

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Preparar y ejecutar la consulta para buscar al usuario
    $query = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':email', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si el usuario existe y la contraseña es correcta
    if ($user && password_verify($password, $user['password'])) {
        // Iniciar sesión y almacenar información del usuario
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario_nombre'] = $user['nombre'];
        $_SESSION['rol'] = $user['rol'];
        $_SESSION['division'] = $user['division']; // Almacenar la división
        $_SESSION['LAST_ACTIVITY'] = time(); // Actualizar la última actividad

        // Redirigir según el rol del usuario
        if ($user['rol'] == 'admin') {
            header("Location: pages/index.php");  // Redirigir a una página de administrador
            exit();
        } else {
            header("Location: pages/index.php"); // Redirigir a una página para usuarios regulares
            exit();
        }
    } else {
        $error_message = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Tesis Jorge</title>
    <link rel="apple-touch-icon" sizes="180x180" href="./favicon/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="./favicon/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="./favicon/favicon-16x16.png" />
    <link rel="manifest" href="./favicon/site.webmanifest" />
    <link rel="mask-icon" href="./favicon/safari-pinned-tab.svg" color="#5bbad5" />
    <meta name="msapplication-TileColor" content="#da532c" />
    <meta name="theme-color" content="#ffffff" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!--css-->
    <link rel="stylesheet" href="./estilos/index.css" />
</head>

<body class="fondo-con-opacidad">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <h3 class="text-center">
                    <strong class="text-dark text-underline"> Iniciar Sesión</strong> <br />Sistema de Control de
                    Soldados
                </h3>
                <div class="alert alert-danger">Identifiquese para acceder</div>
                <form method="POST" action="login.php">
                    <div class="mb-3">
                        <label for="username" class="form-label">Usuario</label>
                        <input type="text" name="username" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" required />
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
                    <button type="button" class="btn btn-secondary w-100 mt-3" id="btnRegistrar">Registrar
                        Soldado</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal de Error -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="errorModalLabel">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span id="errorMessage" class="text-danger">Contraseña incorrecta: Solo administradores tienen
                        acceso</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function() {
        <?php if (isset($error_message)): ?>
        $('#errorMessage').text("<?php echo $error_message; ?>");
        $('#errorModal').modal('show');
        <?php endif; ?>
    });
    $('#btnRegistrar').on('click', function() {
        window.location.href = 'registrar.php';
    });
    </script>

</body>

</html>