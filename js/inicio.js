// Inicializar la DataTable cuando el documento esté listo
$(document).ready(function () {
  var table;
});

// Función para inicializar la DataTable
function iniciarDataTable(rol) {
  table = $('#miTabla').DataTable({
    paging: true,
    searching: true,
    destroy: true,
    ajax: {
      type: 'POST',
      url: '../scripts/consultar_soldados.php',
      dataSrc: function (json) {
        if (json.exito === 'S') {
          return json.resultados; // Retorna los resultados si la respuesta fue exitosa
        } else {
          console.error(json.textoerror);
          return []; // Retorna un array vacío si hubo un error
        }
      },
    },
    columns: [
      { data: 'apellido_nombre', title: 'APELLIDO Y NOMBRE' },
      { data: 'grado', title: 'GRADO' },
      { data: 'dni', title: 'DNI' },
      { data: 'fecha', title: 'NACIMIENTO' },
      { data: 'antiguedad', title: 'INGRESO' },
      {
        // Nueva columna de antigüedad
        title: 'ANTIGÜEDAD',
        data: 'antiguedad',
        render: function (data, type, row) {
          // Calcular la antigüedad dinámica a partir de la fecha de ingreso
          var fechaActual = new Date();
          var fechaAntiguedad = new Date(data.split('/').reverse().join('-')); // Convertir a YYYY-MM-DD
          var antiguedad = fechaActual.getFullYear() - fechaAntiguedad.getFullYear();
          var mesActual = fechaActual.getMonth();
          var mesIngreso = fechaAntiguedad.getMonth();

          // Ajustar la antigüedad si la fecha de ingreso no ha ocurrido aún este año
          if (
            mesIngreso > mesActual ||
            (mesIngreso === mesActual && fechaAntiguedad.getDate() > fechaActual.getDate())
          ) {
            antiguedad--;
          }

          return antiguedad + ' años';
        },
      },
      { data: 'observaciones', title: 'OBSERVACIONES' },
      { data: 'division', title: 'DIVISIÓN' },
      {
        // Columna de acción
        data: null,
        render: function (data, type, row) {
          return (
            '<button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar" data-registro=\'' +
            JSON.stringify(row) +
            "'>Editar</button>" +
            '<button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalEliminar" data-registro=\'' +
            JSON.stringify(row) +
            "'>Eliminar</button>"
          );
        },
      },
    ],
    language: {
      lengthMenu: 'Mostrar _MENU_ registros por página',
      zeroRecords: 'No se encontraron resultados',
      info: 'Mostrando página _PAGE_ de _PAGES_',
      infoEmpty: 'No hay registros disponibles',
      infoFiltered: '(filtrado de _MAX_ registros totales)',
      search: 'Buscar:',
      paginate: {
        first: 'Primero',
        last: 'Último',
        next: 'Siguiente',
        previous: 'Anterior',
      },
      loadingRecords: 'Cargando...',
      processing: 'Procesando...',
      emptyTable: 'No hay datos disponibles en la tabla',
      thousands: ',',
      decimal: '.',
      aria: {
        sortAscending: ': activar para ordenar la columna ascendente',
        sortDescending: ': activar para ordenar la columna descendente',
      },
    },
  });

  // Ocultar la columna de acciones si el rol es 'usuario'
  if (rol === 'usuario') {
    table.column(8).visible(false); // Índice 8 corresponde a la columna de acciones
  }
}

// Evento para el botón "Editar"
$('#miTabla tbody').on('click', 'button[data-bs-target="#modalEditar"]', function () {
  var registro = $(this).attr('data-registro');
  registro = JSON.parse(registro); // Convertir la cadena JSON a objeto
  console.log('Fecha recibida:', registro.fecha);

  // Verificar si el registro contiene el ID
  console.log('Registro completo:', registro);

  // Rellenar el formulario con los datos del registro
  $('#apellido_nombre').val(registro.apellido_nombre);
  $('#grado').val(registro.grado);
  $('#dni').val(registro.dni);
  $('#fecha').val(registro.fecha); // Esta es la fecha de nacimiento
  $('#antiguedad').val(registro.antiguedad); // Esta es la fecha de ingreso al ejercito
  $('#observaciones').val(registro.observaciones);
  $('#selectDivision').val(registro.division);

  // Almacenar el ID del registro para su uso posterior
  $('#formEditar').data('id', registro.id);
  console.log('ID almacenado:', registro.id);

  // Calcular edad a partir de la fecha de nacimiento
  var fechaNacimiento = new Date(registro.fecha.split('/').reverse().join('-')); // Cambiar formato a YYYY-MM-DD
  var fechaActual = new Date();
  var edad = fechaActual.getFullYear() - fechaNacimiento.getFullYear();
  var mesActual = fechaActual.getMonth();
  var mesNacimiento = fechaNacimiento.getMonth();

  // Ajustar la edad si el cumpleaños no ha ocurrido aún este año
  if (mesNacimiento > mesActual || (mesNacimiento === mesActual && fechaNacimiento.getDate() > fechaActual.getDate())) {
    edad--;
  }

  // Insertar la edad en el input correspondiente
  $('#edad').val(edad);
  console.log('Edad:', edad);

  // Calcular antigüedad dinámica a partir de la fecha de ingreso
  var fechaAntiguedad = new Date(registro.antiguedad.split('/').reverse().join('-')); // Cambiar formato a YYYY-MM-DD
  var antiguedad = fechaActual.getFullYear() - fechaAntiguedad.getFullYear();
  var mesAntiguedad = fechaActual.getMonth();
  var mesAntiguedadInicio = fechaAntiguedad.getMonth();

  // Ajustar la antigüedad si la fecha de inicio no ha ocurrido aún este año
  if (
    mesAntiguedadInicio > mesAntiguedad ||
    (mesAntiguedadInicio === mesAntiguedad && fechaAntiguedad.getDate() > fechaActual.getDate())
  ) {
    antiguedad--;
  }

  // Insertar la antigüedad dinámica en el input correspondiente
  $('#antiguedadDinamica').val(antiguedad);
  console.log('Antigüedad dinámica:', antiguedad);
});

// Evento para el botón "Guardar cambios"
$('#btnEditar').on('click', function () {
  var id = $('#formEditar').data('id'); // Obtener el ID del registro
  console.log('ID:', id); // Para verificar que el ID esté presente

  var dni = $('#dni').val();
  var fecha = $('#fecha').val();
  var antiguedad = $('#antiguedad').val();

  // Obtener la fecha actual
  var hoy = new Date();
  var anioActual = hoy.getFullYear();
  hoy.setHours(0, 0, 0, 0); // Ajustar la hora para comparar solo la fecha

  // Verificar que el DNI tenga máximo 8 dígitos
  if (dni.length > 8) {
    Swal.fire({
      title: 'Error!',
      text: 'El DNI no puede tener más de 8 dígitos.',
      icon: 'warning',
      confirmButtonText: 'OK',
    });
    return; // Detener la ejecución si el DNI es inválido
  }

  // Validar la fecha de nacimiento
  var fechaNacimiento = new Date(fecha.split('/').reverse().join('-')); // Convertir a formato yyyy-mm-dd
  if (fechaNacimiento >= new Date(anioActual, 0, 1)) {
    Swal.fire({
      title: 'Error!',
      text: 'La fecha de nacimiento debe ser anterior al año actual.',
      icon: 'warning',
      confirmButtonText: 'OK',
    });
    return; // Detener la ejecución si la fecha es inválida
  }

  // Validar la antigüedad
  var fechaAntiguedad = new Date(antiguedad.split('/').reverse().join('-')); // Convertir a formato yyyy-mm-dd
  if (fechaAntiguedad >= hoy) {
    Swal.fire({
      title: 'Error!',
      text: 'La fecha de antigüedad no puede ser mayor o igual a la fecha actual.',
      icon: 'warning',
      confirmButtonText: 'OK',
    });
    return; // Detener la ejecución si la fecha es inválida
  }

  // Verificar si el DNI ya está en uso en la base de datos
  $.ajax({
    type: 'POST',
    url: '../scripts/crud_soldados.php',
    data: {
      accion: 'VERIFICAR_DNI',
      dni: dni,
      id: id, // Enviar el ID para excluir el registro actual de la verificación
    },
    success: function (response) {
      response = typeof response === 'string' ? JSON.parse(response) : response; // Asegurarse de que es un objeto

      if (response.existe) {
        Swal.fire({
          title: 'DNI en uso',
          text: 'Este DNI ya está registrado en la base de datos.',
          icon: 'error',
          confirmButtonText: 'OK',
        });
      } else {
        // Continuar con la actualización si el DNI no está en uso
        actualizarRegistro(id);
      }
    },

    error: function (xhr, status, error) {
      Swal.fire({
        title: 'Error!',
        text: 'Error en la conexión: ' + error,
        icon: 'error',
        confirmButtonText: 'OK',
      });
    },
  });
});

// Función para actualizar el registro después de verificar el DNI
function actualizarRegistro(id) {
  // Crear un objeto data sin las fechas
  var data = {
    id: id,
    apellido_nombre: $('#apellido_nombre').val(),
    grado: $('#grado').val(),
    dni: $('#dni').val(),
    fecha: $('#fecha').val(),
    antiguedad: $('#antiguedad').val(),
    division: $('#modalEditar #selectDivision').val(),
    observaciones: $('#observaciones').val(),
  };

  console.log('Datos enviados:', data); // Verifica los datos enviados

  // Enviar la solicitud AJAX al archivo PHP para actualizar
  $.ajax({
    type: 'POST',
    url: '../scripts/crud_soldados.php',
    data: {
      accion: 'ACTUALIZAR_REGISTRO',
      data: data,
    },
    success: function (response) {
      Swal.fire({
        title: 'Se actualizó el registro',
        text: 'Se actualizó el registro de manera exitosa',
        icon: 'success',
        confirmButtonText: 'OK',
      });
      console.log('Respuesta recibida:', response); // Para depurar la respuesta
      $('#modalEditar').modal('hide'); // Cerrar el modal
      iniciarDataTable(); // Refresca la tabla
    },
    error: function (xhr, status, error) {
      Swal.fire({
        title: 'Error!',
        text: 'Error en la conexión: ' + error,
        icon: 'error',
        confirmButtonText: 'OK',
      });
    },
  });
}

// Evento para el botón "Eliminar"
$('#miTabla tbody').on('click', 'button[data-bs-target="#modalEliminar"]', function () {
  var registro = $(this).attr('data-registro');

  registro = JSON.parse(registro); // Convertir la cadena JSON a objeto

  // Almacenar el ID del registro para su uso posterior
  $('#btnEliminar').data('id', registro.id); // Almacena el ID en el botón de eliminar
  console.log('ID almacenado para eliminar:', registro.id); // Verifica que el ID se almacene correctamente
});

// Evento para el botón "Eliminar" en el modal
$('#btnEliminar').on('click', function () {
  var id = $(this).data('id'); // Obtener el ID almacenado
  console.log('ID a eliminar:', id); // Depurar el ID

  if (!id) {
    console.error('ID no está definido'); // Manejo de error si ID no está definido
    return; // Salir de la función si ID no está presente
  }

  // Enviar la solicitud AJAX al archivo PHP para eliminar el registro
  $.ajax({
    type: 'POST',
    url: '../scripts/crud_soldados.php',
    data: {
      accion: 'ELIMINAR_REGISTRO',
      id: id,
    },
    success: function (response) {
      console.log('Respuesta de eliminación:', response); // Para depurar la respuesta

      // Asegúrate de que tu respuesta tenga esta estructura
      Swal.fire({
        title: 'Registro Eliminado',
        text: 'El registro se eliminó correctamente.',
        icon: 'success',
        confirmButtonText: 'OK',
      });
      $('#modalEliminar').modal('hide'); // Cerrar el modal
      iniciarDataTable(); // Refrescar la tabla
    },
    error: function (xhr, status, error) {
      Swal.fire({
        title: 'Error!',
        text: 'Error al eliminar el registro: ' + error,
        icon: 'error',
        confirmButtonText: 'OK',
      });
    },
  });
});
// Evento para filtrar por división
$('#selectDivision').on('change', function () {
  var division = $(this).val(); // Obtener el valor seleccionado
  if (division) {
    console.log('Valor de división seleccionado:', division);
    table.column(7).search(division).draw();
    console.log('Filtrado por división:', division);
  } else {
    table.column(7).search('').draw();
  }
});
