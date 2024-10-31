<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    // Redirigir a la página de inicio de sesión si no está autenticado
    header("Location: login.php");
    exit();
}

// Verificar el rol del usuario
$rol = $_SESSION['rol'];
if ($rol == 'admin') {
    // Si es admin (rol 1)
    $mensaje = "Bienvenido Administrador";
} elseif ($rol == 'usuario') {
    // Si es usuario (rol 2)
    $mensaje = "Bienvenido ";
} else {
    // Si el rol no es válido o no está definido
    header("Location: login.php");
    exit();
}

$divisiones = [
    '' => '--TODOS LOS SOLDADOS--',  // Opción por defecto
    '1' => '1- PERSONAL',
    '2' => '2- INTELIGENCIA',
    '3' => '3- OPERACIONES',
    '4' => '4- MATERIALES',
    '5' => '5- PRESUPUESTO'
];

if ($rol == 'usuario'): 
    // Obtener el número de división de la sesión
    $divisionNumero = $_SESSION['division'];
    // Obtener el nombre de la división usando el número
    $divisionNombre = isset($divisiones[$divisionNumero]) ? $divisiones[$divisionNumero] : 'División desconocida';
    //echo $divisionNombre; // Mostrar el nombre de la división
endif; // Cerrar el bloque if del rol usuario
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Tesis Jorge</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png" />
    <link rel="manifest" href="../favicon/site.webmanifest" />
    <link rel="mask-icon" href="../favicon/safari-pinned-tab.svg" color="#5bbad5" />
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
    <link rel="stylesheet" href="../estilos/index.css?v=1.1.0" />
</head>
<nav class="navbar navbar-light bg-light">
    <?php if ($rol == 'admin'): ?>
    <a class="navbar-brand" href="agregarSoldado.php">
        <img src="../images/icono-casco.jpg" width="30" height="30" alt="" />
        Agregar Soldado
    </a>
    <?php endif; ?>
    <a class="navbar-brand text-danger" href="../scripts/logout.php">
        <img src="../images/icono-casco.jpg" width="30" height="30" alt="" />
        Cerrar Sesión
    </a>
    <?php if ($rol == 'usuario'): ?>
    <p class="navbar-brand text-success">División <?php echo htmlspecialchars($divisionNombre); ?></p>
    <?php endif; ?>
</nav>

<body class="fondo-con-opacidad">
    <div class="container mt-5 pt-2">
        <h1 class="text-center fw-bold text-white">S.C.S</h1>
        <h1 class="text-center fw-bold text-white mb-4">SISTEMA DE CONTROL DE SOLDADOS</h1>
        <h5 id="xyz" class="text-center text-bold mt-2"></h5>
        <label for="selectDivision">Seleccione la división</label>
        <select name="division" id="selectDivision">
            <option value="">--TODOS LOS SOLDADOS--</option>
            <option value="1">1- PERSONAL</option>
            <option value="2">2- INTELIGENCIA</option>
            <option value="3">3- OPERACIONES</option>
            <option value="4">4- MATERIALES</option>
            <option value="5">5- PRESUPUESTO</option>
        </select>
        <table id="miTabla" class="table table-striped table-bordered" style="width: 100%">
            <thead>
                <tr class="text-center">
                    <th>APELLIDO Y NOMBRE</th>
                    <th>GRADO</th>
                    <th>DIVISION</th>
                    <th>DNI</th>
                    <th>FECHA DE NACIMIENTO</th>
                    <th>ANTIGUEDAD</th>
                    <th>OBSERVACIONES</th>
                    <th></th>
                    <th>ACCION</th>
                </tr>
            </thead>
            <tbody>
                <!--datos llenados dinamicamente desde bdd-->
            </tbody>
        </table>
    </div>
    <!-- Modal para editar o eliminar -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark" id="modalEditarLabel">Editar Registro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalEditar">
                    <!-- Formulario para editar -->
                    <form id="formEditar">
                        <div class="row">
                            <!-- Apellido y Nombre -->
                            <div class="col-md-6 mb-3">
                                <label for="apellido_nombre" class="form-label">Apellido y Nombre</label>
                                <input type="text" class="form-control" id="apellido_nombre" name="apellido_nombre" />
                            </div>

                            <!-- Grado -->
                            <div class="col-md-6 mb-3">
                                <label for="selectGrado" class="form-label">Grado</label>
                                <select name="grado" id="grado" class="form-control" required>
                                    <option value="soldado_2da_comision">Soldado de 2da en comisión</option>
                                    <option value="soldado_2da">Soldado de 2da</option>
                                    <option value="soldado_1ra">Soldado de 1ra</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <!-- DNI  -->
                            <div class="col-md-5 mb-3">
                                <label for="dni" class="form-label">DNI</label>
                                <input type="number" class="form-control" id="dni" required max="99999999" />
                            </div>
                            <!-- Fecha de nacimiento -->
                            <div class="col-md-5 mb-3">
                                <label for="fecha" class="form-label">Fecha de nacimiento </label>
                                <input type="text" class="form-control" id="fecha" name="fecha" />
                                <div class="invalid-feedback">Formato de fecha incorrecto. Debe ser AAAA-MM-DD.</div>
                            </div>
                            <!-- Edad -->
                            <div class="col-md-2 mb-3">
                                <label for="edad" class="form-label">Edad (años)</label>
                                <input type="text" class="form-control" id="edad" name="edad" readonly />
                            </div>
                        </div>
                        <div class="row">
                            <!-- Antigüedad -->
                            <div class="col-md-5 mb-3">
                                <label for="antiguedad" class="form-label">Antigüedad (Fecha Ingreso)</label>
                                <input type="text" class="form-control" id="antiguedad" name="antiguedad" />
                                <div class="invalid-feedback">Formato de fecha incorrecto. Debe ser AAAA-MM-DD.
                                </div>
                            </div>
                            <!-- División -->
                            <div class="col-md-5 mb-3">
                                <label for="selectDivision" class="form-label">División</label>
                                <select name="division" id="selectDivision" class="form-control">
                                    <option value="1">1- PERSONAL</option>
                                    <option value="2">2- INTELIGENCIA</option>
                                    <option value="3">3- OPERACIONES</option>
                                    <option value="4">4- MATERIALES</option>
                                    <option value="5">5- PRESUPUESTO</option>
                                </select>
                            </div>
                            <!-- Antiguedad dinamica-->
                            <div class="col-md-2 mb-3">
                                <label for="edad" class="form-label">Antigüedad (desde ingreso)</label>
                                <input type="text" class="form-control" id="antiguedadDinamica" name="edad" readonly />
                            </div>
                            <!-- Observaciones -->
                            <div class="col-md-12 mb-3">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <textarea class="form-control" id="observaciones" name="observaciones"
                                    rows="3"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnEditar">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de eliminación -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark" id="modalEliminarLabel">Eliminar Registro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-dark">¿Estás seguro de que deseas eliminar este registro?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btnEliminar">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    // Inicializar la DataTable cuando el documento esté listo
    $(document).ready(function() {
        var table;
        var rol =
            '<?php echo htmlspecialchars($rol, ENT_QUOTES, 'UTF-8'); ?>'; // Obtén el rol del usuario desde PHP
        var divisionNombre =
            '<?php echo htmlspecialchars($divisionNombre ?? "División desconocida", ENT_QUOTES, 'UTF-8'); ?>'; // Usar un valor por defecto
        iniciarDataTable(rol);
        console.log('Rol:', rol);

        if (rol !== 'admin') {
            $('#xyz').text('División: ' + divisionNombre);
        } else {
            $('#xyz').text('PERFIL SUPER    ADMINISTRADOR ');
        }
    });
    </script>
    <script src="../js/inicio.js?v=<?php echo time(); ?>"></script>
</body>

</html>