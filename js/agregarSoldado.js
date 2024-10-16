$(document).ready(function () {
  document.getElementById('dni').addEventListener('input', function (e) {
    this.value = this.value.replace(/[^0-9]/g, ''); // Remueve cualquier carácter que no sea numérico
  });

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
      console.log(datos);

      Swal.fire({
        title: 'Error!',
        text: 'Por favor completa todos los campos del formulario.',
        icon: 'warning',
        confirmButtonText: 'OK',
      });
      return; // Detener la ejecución si falta algún campo
    }
    console.log(datos);

    // Enviar los datos a través de AJAX
    $.ajax({
      url: '../scripts/crud_soldados.php',
      type: 'POST',
      data: {
        data: datos,
        accion: 'CREAR_REGISTRO', // Agregamos la acción
      },
      dataType: 'json',
      success: function (response) {
        console.log('Respuesta recibida:', response);
        // Manejar la respuesta
        if (response.success) {
          Swal.fire({
            title: 'Se creó el nuevo registro de soldado',
            text: response.message,
            icon: 'success',
            confirmButtonText: 'OK',
          }),
            // Opcional: Limpiar el formulario
            $('#formAgregarSoldado')[0].reset();
        } else {
          Swal.fire({
            title: 'Error!',
            text: 'Error en la conexión: ' + response.error,
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
  });
});
