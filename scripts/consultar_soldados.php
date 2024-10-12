<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    // Redirigir a la página de inicio de sesión si no está autenticado
    header("Location: login.php");
    exit();
}

// Incluir el archivo de conexión a la base de datos
include('conexion.php');

class Respuesta {
    public $exito;
    public $textoerror;
    public $resultados;
}

// Función para formatear la fecha
function fechaddmmyyyy($cambio) {
    $dd = substr($cambio, 8, 2);
    $mm = substr($cambio, 5, 2);
    $aa = substr($cambio, 0, 4);
    $resul = ($dd . "/" . $mm . "/" . $aa);
    return $resul;
}

// Inicializa la respuesta
$respuesta = new Respuesta();

try {
    // Consulta para obtener los soldados con la nueva columna 'division'
    $sql = "SELECT id, apellido_nombre, grado, dni, fecha, antiguedad, observaciones, division FROM soldados";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    
    // Obtener los resultados
    $soldados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($soldados)) {
        $respuesta->exito = "N";
        $respuesta->textoerror = "No se encontraron soldados.";
    } else {
        $respuesta->exito = "S";
        
        // Formatear las fechas en los resultados
        foreach ($soldados as &$soldado) {
            $soldado['fecha'] = fechaddmmyyyy($soldado['fecha']); // Aplica el formato a la fecha
            $soldado['antiguedad'] = fechaddmmyyyy($soldado['antiguedad']); // Aplica el formato a la antigüedad
        }
        
        $respuesta->resultados = $soldados; // Incluir los resultados con la columna 'division'
    }

    // Devolver los datos en formato JSON
    header('Content-Type: application/json');
    echo json_encode($respuesta);

} catch (PDOException $e) {
    header('Content-Type: application/json'); // Asegúrate de que el contenido sea JSON
    echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
}
?>