<?php
// Iniciar sesión en PHP
session_start();

// Verificar si existe un mensaje de éxito en la sesión
if (!isset($_SESSION['success_message'])) {
    // Si no hay mensaje de éxito, redirigir a la página de registro o inicio
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Registro Exitoso</title>
    <link rel="apple-touch-icon" sizes="180x180" href="./favicon/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="./favicon/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="./favicon/favicon-16x16.png" />
    <link rel="manifest" href="./favicon/site.webmanifest" />
    <link rel="mask-icon" href="./favicon/safari-pinned-tab.svg" color="#5bbad5" />
    <meta name="msapplication-TileColor" content="#da532c" />
    <meta name="theme-color" content="#ffffff" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!--css-->
    <link rel="stylesheet" href="./estilos/index.css" />
</head>

<body class="fondo-con-opacidad">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>Registro Exitoso</h2>
                    </div>
                    <div class="card-body text-center">
                        <p class="lead">
                            <?php
                            // Mostrar el mensaje de éxito
                            echo $_SESSION['success_message'];
                            // Eliminar el mensaje de éxito de la sesión
                            unset($_SESSION['success_message']);
                            ?>
                        </p>
                        <a href="index.php" class="btn btn-primary mt-3">Volver al inicio</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estilos personalizados -->
    <style>
    .fondo-con-opacidad {
        background: rgba(0, 0, 0, 0.1);
    }
    </style>
</body>

</html>