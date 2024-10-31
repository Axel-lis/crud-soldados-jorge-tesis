<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    // Redirigir a la página de inicio de sesión si no está autenticado
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
    <title>Tesis Jorge</title>
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png" />
    <link rel="manifest" href="/favicon/site.webmanifest" />
    <link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#5bbad5" />
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
    <link rel="stylesheet" href="../estilos/index.css?v=1.1" />
</head>
<!-- Just an image -->
<nav class="navbar navbar-light bg-light">
    <a class="navbar-brand" href="index.php">
        <img src="../images/icono-casco.jpg" width="30" height="30" alt="" />
        Volver al Inicio
    </a>
    <a class="navbar-brand text-danger" href="../scripts/logout.php">
        <img src="../images/icono-casco.jpg" width="30" height="30" alt="" />
        Cerrar Sesión
    </a>
</nav>

<body class="fondo-con-opacidad">
    <!-- Formulario para agregar soldado -->
    <div class="container mb-5 pt-5">
        <h3 class="text-center fw-bold text-white mt-5">Formulario para agregar soldado</h3>
        <form id="formAgregarSoldado">
            <div class="text-bold">
                <label for="nombreApellido" class="form-label">Apellido y Nombre</label>
                <input type="text" class="form-control" id="nombreApellido" required />
            </div>
            <div class="text-bold">
                <label for="selectGrado" class="form-label">Grado</label>
                <select name="grado" id="selectGrado" class="form-control" required>
                    <option value="soldado_2da_comision">Soldado de 2da en comisión</option>
                    <option value="soldado_2da">Soldado de 2da</option>
                    <option value="soldado_1ra">Soldado de 1ra</option>
                </select>
            </div>

            <div class="text-bold">
                <label for="dni" class="form-label">DNI</label>
                <input type="number" class="form-control" id="dni" required max="99999999" />
            </div>

            <div class="text-bold">
                <label for="fecha" class="form-label">Fecha nacimiento</label>
                <input type="date" class="form-control" id="fecha" required />
            </div>

            <!-- Antigüedad -->
            <div class="text-bold">
                <label for="antiguedad" class="form-label">Antigüedad (Fecha Ingreso)</label>
                <input type="date" class="form-control" id="antiguedad" name="antiguedad" required />
            </div>
            <div class="text-bold">
                <label for="selectDivision" class="form-label">División</label>
                <select name="division" id="selectDivision" class="form-control" required>
                    <option value="1">1- PERSONAL</option>
                    <option value="2">2- INTELIGENCIA</option>
                    <option value="3">3- OPERACIONES</option>
                    <option value="4">4- MATERIALES</option>
                    <option value="5">5- PRESUPUESTO</option>
                </select>
            </div>
            <div class="text-bold">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea class="form-control" id="observaciones" rows="3"></textarea>
            </div>
            <button type="button" class="btn btn-success mt-4" id="btnAgregar">Agregar Soldado</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/agregarSoldado.js?v=1.3.3"></script>
</body>


</html>