$(document).ready(function () {
  // Limitar el input de DNI a números y un máximo de 8 caracteres
  $('#dni').on('input', function () {
    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8);
  });

  // Establecer la fecha mínima y máxima en el input de fecha
  const today = new Date();
  const nextYear = today.getFullYear() + 1;
  const minDate = new Date(nextYear, 0, 1); // 1 de enero del próximo año
  const maxDate = new Date(today.getFullYear(), 11, 31); // 31 de diciembre del año actual
  $('#fecha').attr('min', minDate.toISOString().split('T')[0]);
  $('#fecha').attr('max', maxDate.toISOString().split('T')[0]);

  // Evento de control del btnAgregar
  $('#btnAgregar').on('click', function () {
    // Obtener los datos del formulario
    var datos = {
      apellido_nombre: $('#nombreApellido').val(),
      grado: $('#selectGrado').val(),
      dni: $('#dni').val(),
      fecha: $('#fecha').val(),
      antiguedad: $('#antiguedad').val(),
      division: $('#selectDivision').val(),
      observaciones: $('#observaciones').val(),
    };

    // Depuración: mostrar los datos en la consola
    console.log(datos);

    // Validación: comprobar que todos los campos no estén vacíos
    if (
      !datos.apellido_nombre ||
      !datos.grado ||
      !datos.dni ||
      !datos.fecha ||
      !datos.antiguedad ||
      !datos.division ||
      !datos.observaciones
    ) {
      Swal.fire({
        title: 'Error!',
        text: 'Por favor completa todos los campos del formulario.',
        icon: 'warning',
        confirmButtonText: 'OK',
      });
      return; // Detener la ejecución si falta algún campo
    }

    // Validación: verificar si la fecha es válida
    const selectedDate = new Date(datos.fecha);
    if (selectedDate >= minDate) {
      Swal.fire({
        title: 'Fecha no válida',
        text: 'La fecha de nacimiento debe ser anterior al año actual.',
        icon: 'warning',
        confirmButtonText: 'OK',
      });
      return; // Detener la ejecución si la fecha no es válida
    }

    // Validación: verificar si el DNI ya está registrado
    $.ajax({
      type: 'POST',
      url: '../scripts/crud_soldados.php',
      data: {
        accion: 'VERIFICAR_DNI',
        dni: datos.dni,
      },
      success: function (response) {
        var res = JSON.parse(response);

        if (res.existe) {
          Swal.fire({
            title: 'DNI en uso',
            text: 'Este DNI ya está registrado en la base de datos.',
            icon: 'error',
            confirmButtonText: 'OK',
          });
        } else {
          // Continuar con la creación si el DNI no está en uso
          crearRegistro(datos);
        }
      },
      error: function (xhr, status, error) {
        Swal.fire({
          title: 'Error!',
          text: 'Error en la petición al servidor: ' + error,
          icon: 'error',
          confirmButtonText: 'OK',
        });
      },
    });
  });

  // Función para crear el registro
  function crearRegistro(datos) {
    $.ajax({
      url: '../scripts/crud_soldados.php',
      type: 'POST',
      data: {
        data: datos,
        accion: 'CREAR_REGISTRO',
      },
      dataType: 'json',
      success: function (response) {
        // Manejar la respuesta
        if (response.success) {
          Swal.fire({
            title: 'Registro creado',
            text: response.message,
            icon: 'success',
            confirmButtonText: 'OK',
          });
          // Limpiar el formulario
          $('#formAgregarSoldado')[0].reset();
        } else {
          Swal.fire({
            title: 'Error!',
            text: response.message,
            icon: 'error',
            confirmButtonText: 'OK',
          });
        }
      },
      error: function (xhr, status, error) {
        Swal.fire({
          title: 'Error!',
          text: 'Error en la petición al servidor: ' + error,
          icon: 'error',
          confirmButtonText: 'OK',
        });
      },
    });
  }
});
