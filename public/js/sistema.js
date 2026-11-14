// ===== Funciones generales del sistema (jQuery) =====

// Token CSRF para todas las peticiones AJAX
$.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
});

// Cerrar sesión
$('#btnSalir').on('click', function (e) {
    e.preventDefault();
    $('#formSalir').submit();
});

// ===== DataTables =====
var idiomaTabla = {
    search: 'Buscar:',
    lengthMenu: 'Mostrar _MENU_ registros',
    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
    infoEmpty: 'Sin registros',
    infoFiltered: '(filtrado de _MAX_ registros)',
    zeroRecords: 'No se encontraron resultados',
    emptyTable: 'No hay datos en la tabla',
    paginate: { first: 'Primero', last: 'Último', next: 'Siguiente', previous: 'Anterior' }
};

// Crea una DataTable. Si exportar es true agrega los botones de Excel y PDF
function crearTabla(selector, exportar, titulo) {
    var opciones = { language: idiomaTabla, pageLength: 10 };

    if (exportar) {
        opciones.dom = "<'row'<'col-md-6'B><'col-md-6'f>>" +
                       "<'row'<'col-12'tr>>" +
                       "<'row'<'col-md-5'i><'col-md-7'p>>";
        opciones.buttons = [
            { extend: 'excelHtml5', text: 'Excel', className: 'btn-success btn-sm', title: titulo },
            { extend: 'pdfHtml5', text: 'PDF', className: 'btn-danger btn-sm', title: titulo }
        ];
    }

    return $(selector).DataTable(opciones);
}

// ===== Mensajes con SweetAlert =====
function mensajeOk(texto) {
    Swal.fire('¡Buen trabajo!', texto, 'success');
}

function mostrarError(xhr) {
    var json = xhr.responseJSON;

    if (json && json.errors) {
        var lista = Object.values(json.errors).flat().join('<br>');
        Swal.fire({ icon: 'error', title: 'Revisa los datos', html: lista });
    } else if (json && json.mensaje) {
        Swal.fire('Atención', json.mensaje, 'warning');
    } else if (xhr.status === 401 || xhr.status === 419) {
        Swal.fire('Sesión expirada', 'Vuelve a iniciar sesión.', 'warning').then(function () {
            location.href = '/login';
        });
    } else if (xhr.status === 403) {
        Swal.fire('Sin permiso', 'No tienes permiso para hacer esto.', 'error');
    } else {
        Swal.fire('Error', 'Ocurrió un problema en el servidor.', 'error');
    }
}

// ===== CRUD con modal (clientes, vehículos, espacios, usuarios) =====
// config: nombre, url, modal, form, tabla, cuerpo (y alRecargar, que es opcional)
function iniciarCrud(config) {
    var tabla = crearTabla(config.tabla);

    // Vuelve a cargar las filas de la tabla desde el servidor
    function recargar() {
        tabla.destroy();
        $(config.cuerpo).load(config.url + '/tabla', function () {
            tabla = crearTabla(config.tabla);

            if (config.alRecargar) config.alRecargar();
        });
    }

    // Abrir el modal para añadir
    $('.btn-nuevo').on('click', function () {
        $(config.form)[0].reset();
        $(config.form + ' [name="id"]').val('');
        $(config.modal + ' .modal-title').text('Añadir ' + config.nombre);
        $(config.modal).modal('show');
    });

    // Abrir el modal para editar (los datos vienen en el botón)
    $(document).on('click', '.btn-editar', function () {
        var datos = $(this).data('json');

        $(config.form)[0].reset();
        $.each(datos, function (campo, valor) {
            var input = $(config.form + ' [name="' + campo + '"]');

            if (input.is(':checkbox')) {
                input.prop('checked', valor == 1);
            } else {
                input.val(valor === null ? '' : valor);
            }
        });

        $(config.modal + ' .modal-title').text('Editar ' + config.nombre);
        $(config.modal).modal('show');
    });

    // Guardar (crear o actualizar)
    $(config.form).on('submit', function (e) {
        e.preventDefault();

        var id = $(config.form + ' [name="id"]').val();

        $.ajax({
            url: id ? config.url + '/' + id : config.url,
            type: id ? 'PUT' : 'POST',
            data: $(config.form).serialize(),
            success: function (resp) {
                $(config.modal).modal('hide');
                recargar();
                mensajeOk(resp.mensaje);
            },
            error: mostrarError
        });
    });

    // Activar o desactivar (no se elimina nada)
    $(document).on('click', '.btn-estado', function () {
        var id = $(this).data('id');
        var accion = $(this).data('accion');

        Swal.fire({
            title: '¿' + accion + ' ' + config.nombre + '?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, ' + accion.toLowerCase(),
            cancelButtonText: 'Cancelar'
        }).then(function (resultado) {
            if (!resultado.isConfirmed) return;

            $.ajax({
                url: config.url + '/' + id + '/estado',
                type: 'POST',
                success: function (resp) {
                    recargar();
                    mensajeOk(resp.mensaje);
                },
                error: mostrarError
            });
        });
    });
}
