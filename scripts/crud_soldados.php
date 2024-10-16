<?php
// Incluir la conexión a la base de datos
include 'conexion.php'; // Archivo que contiene la conexión PDO

function enviarRespuesta($success, $message, $error = null) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'error' => $error
    ]);
    exit;
}


// Verificamos que la acción se haya recibido
if (isset($_POST['accion'])) {
    $accion = $_POST['accion'];

    switch ($accion) {
        case 'ACTUALIZAR_REGISTRO':
            actualizarRegistro($conexion, $_POST['data']);
            break;

        case 'ELIMINAR_REGISTRO':
            eliminarRegistro($conexion, $_POST['id']);
            break;

        case 'CREAR_REGISTRO':
            crearRegistro($conexion, $_POST['data']);
            break;

        default:
            enviarRespuesta(false, 'Acción no válida.');
            break;
    }
} else {
    enviarRespuesta(false, 'No se recibió ninguna acción.');
}

// Función para actualizar un registro en la tabla 'soldados' usando PDO
function actualizarRegistro($conexion, $data) {
    // Asegurarse de que se recibió el ID del soldado
    if (isset($data['id']) && !empty($data['id'])) {
        $id = $data['id'];
        $apellido_nombre = isset($data['apellido_nombre']) ? $data['apellido_nombre'] : '';
        $grado = isset($data['grado']) ? $data['grado'] : '';
        $dni = isset($data['dni']) ? $data['dni'] : '';
        // No se recibirán las fechas para la actualización
        $division = isset($data['division']) ? $data['division'] : '';
        $observaciones = isset($data['observaciones']) ? $data['observaciones'] : '';

        // Consulta de actualización sin las fechas
        $sql = "UPDATE soldados 
                SET apellido_nombre = :apellido_nombre, grado = :grado, dni = :dni, 
                    division = :division, observaciones = :observaciones
                WHERE id = :id";

        try {
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':apellido_nombre', $apellido_nombre);
            $stmt->bindParam(':grado', $grado);
            $stmt->bindParam(':dni', $dni);
            // No se asignan parámetros para fecha y antigüedad
            $stmt->bindParam(':division', $division);
            $stmt->bindParam(':observaciones', $observaciones);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                enviarRespuesta(true, 'Registro actualizado correctamente.');
            } else {
                enviarRespuesta(false, 'Error al actualizar el registro.');
            }
        } catch (PDOException $e) {
            enviarRespuesta(false, 'Error en la base de datos: ' . $e->getMessage());
        }
    } else {
        enviarRespuesta(false, 'ID no proporcionado.');
    }
}


// Función para eliminar un registro de la tabla 'soldados'
function eliminarRegistro($conexion, $id) {
    if (!empty($id)) {
        // Consulta de eliminación con PDO
        $sql = "DELETE FROM soldados WHERE id = :id";
        try {
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                enviarRespuesta(true, 'Registro eliminado correctamente.');
            } else {
                enviarRespuesta(false, 'Error al eliminar el registro.');
            }
        } catch (PDOException $e) {
            enviarRespuesta(false, 'Error en la base de datos: ' . $e->getMessage());
        }
    } else {
        enviarRespuesta(false, 'ID no proporcionado.');
    }
}

// Función para crear un nuevo registro en la tabla 'soldados'
function crearRegistro($conexion, $data) {
    $apellido_nombre = isset($data['apellido_nombre']) ? $data['apellido_nombre'] : '';
    $grado = isset($data['grado']) ? $data['grado'] : '';
    $dni = isset($data['dni']) ? $data['dni'] : '';
    $fecha = isset($data['fecha']) ? $data['fecha'] : '';
    $antiguedad = isset($data['antiguedad']) ? $data['antiguedad'] : '';
    $division = isset($data['division']) ? $data['division'] : '';
    $observaciones = isset($data['observaciones']) ? $data['observaciones'] : '';

    // Consulta de inserción con PDO (incluyendo 'division')
    $sql = "INSERT INTO soldados (apellido_nombre, grado, dni, fecha, antiguedad, division, observaciones) 
            VALUES (:apellido_nombre, :grado, :dni, :fecha, :antiguedad, :division, :observaciones)";

    try {
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':apellido_nombre', $apellido_nombre);
        $stmt->bindParam(':grado', $grado);
        $stmt->bindParam(':dni', $dni);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':antiguedad', $antiguedad);
        $stmt->bindParam(':division', $division);  // Agregar este bindParam
        $stmt->bindParam(':observaciones', $observaciones);

        if ($stmt->execute()) {
            enviarRespuesta(true, 'Registro creado correctamente.');
        } else {
            enviarRespuesta(false, 'Error al crear el registro.');
        }
    } catch (PDOException $e) {
        enviarRespuesta(false, 'Error en la base de datos: ' . $e->getMessage());
    }
}

?>