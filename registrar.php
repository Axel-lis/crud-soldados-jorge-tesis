<?php
// Iniciar sesión en PHP
session_start();

// Incluir el archivo de conexión a la base de datos
include('scripts/conexion.php');

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger y sanitizar los datos del formulario
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $apellido = htmlspecialchars(trim($_POST['apellido']));
    $dni = htmlspecialchars(trim($_POST['dni']));
    $fecha = htmlspecialchars(trim($_POST['fecha']));
    $grado = htmlspecialchars(trim($_POST['grado']));
    $antiguedad = htmlspecialchars(trim($_POST['antiguedad']));
    $division = htmlspecialchars(trim($_POST['division']));
    $observaciones = htmlspecialchars(trim($_POST['observaciones']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Hashing the password

    try {
        // Verificar si el DNI ya está registrado
        $query = "SELECT * FROM usuarios WHERE dni = :dni OR email = :email"; // Check both DNI and email
        $stmt = $conexion->prepare($query);
        $stmt->bindParam(':dni', $dni);
        $stmt->bindParam(':email', $email); // Bind email parameter
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $error_message = "El DNI o el email ya se encuentran registrados.";
        } else {
            // Preparar la consulta para insertar el nuevo usuario
            $query = "INSERT INTO usuarios (nombre, apellido, dni, fecha_nacimiento, grado, antiguedad, division, observaciones, email, password, rol) 
            VALUES (:nombre, :apellido, :dni, :fecha, :grado, :antiguedad, :division, :observaciones, :email, :password, 'usuario')";

            $stmt = $conexion->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':dni', $dni);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':grado', $grado);
            $stmt->bindParam(':antiguedad', $antiguedad);
            $stmt->bindParam(':division', $division);
            $stmt->bindParam(':observaciones', $observaciones);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password); // Bind password parameter

            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Usuario registrado exitosamente.";
                header("Location: success.php");
                exit();
            } else {
                $error_message = "Error al registrar el usuario.";
            }
        }
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
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
    <style>
    .fondo-con-opacidad {
        height: 250% !important;
    }

    #formRegistrar,
    .card-body {
        background-color: rgba(225, 225, 225, 0.8);
    }

    label {
        font-weight: bold;
    }
    </style>
</head>

<body class="fondo-con-opacidad">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header">
                        <h4 class="text-center">Registrar soldado</h4>
                    </div>
                    <div class="card-body">
                        <form id="formRegistrar" class="form" action="registrar.php" method="POST">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required />
                            </div>
                            <div class="mb-3">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="apellido" name="apellido" required />
                            </div>
                            <div class="mb-3">
                                <label for="dni" class="form-label">DNI</label>
                                <input type="number" class="form-control" id="dni" name="dni" required />
                            </div>
                            <div class="mb-3">
                                <label for="fecha" class="form-label">Fecha de nacimiento</label>
                                <input type="date" class="form-control" id="fecha" name="fecha" required />
                            </div>
                            <div class="mb-3">
                                <label for="selectGrado" class="form-label">Grado</label>
                                <select name="grado" id="selectGrado" class="form-control" required>
                                    <option value="soldado_2da_comision">Soldado de 2da en comisión</option>
                                    <option value="soldado_2da">Soldado de 2da</option>
                                    <option value="soldado_1ra">Soldado de 1ra</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="antiguedad" class="form-label">Antigüedad (Fecha Ingreso)</label>
                                <input type="date" class="form-control" id="antiguedad" name="antiguedad" required
                                    placeholder="Inserte el año en AAAA-MM-DD" />
                            </div>
                            <div class="mb-3">
                                <label for="selectDivision" class="form-label">División</label>
                                <select name="division" id="selectDivision" class="form-control" required>
                                    <option value="1">1- PERSONAL</option>
                                    <option value="2">2- INTELIGENCIA</option>
                                    <option value="3">3- OPERACIONES</option>
                                    <option value="4">4- MATERIALES</option>
                                    <option value="5">5- PRESUPUESTO</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <textarea class="form-control" id="observaciones" name="observaciones"
                                    rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required />
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required />
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary w-100">Registrar</button>
                            </div>
                        </form>
                        <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger" role="alert"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button class="btn btn-danger btn-sm my-5" id="btnVolver">Volver a la página login</button>
    <script>
    $(document).ready(function() {
        $('#btnVolver').on('click', function() {
            window.location.href = 'login.php';
        })
        <?php if (isset($error_message)): ?>
        $('#errorMessage').text("<?php echo $error_message; ?>");
        $('#errorModal').modal('show');
        <?php endif; ?>
        <?php if (isset($_SESSION['success_message'])): ?>
        alert("<?php echo $_SESSION['success_message']; ?>");
        <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
    });
    </script>
</body>

</html>