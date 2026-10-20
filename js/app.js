$(document).ready(function () {
    // Carga inicial de documentos al entrar a la página
    cargarDocumentos();

    // 1. FILTRADO EXCLUSIVO POR BOTÓN
    $('#btnFiltrar').on('click', function (e) {
        e.preventDefault();
        cargarDocumentos();
    });

    // 2. Manejo de selección de archivo PDF / Cámara
    $('#fileInputCamara, #fileInputPDF').on('change', function () {
        var file = this.files[0];
        if (file) {
            $('#file-name').text(file.name);
            $('#file-preview').slideDown();
        }
    });

    $('#btn-remove-file').on('click', function () {
        $('#fileInputCamara').val('');
        $('#fileInputPDF').val('');
        $('#file-preview').slideUp();
    });

    // 3. Guardar Documento vía AJAX
    $('#formDocumento').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);

        var fileCamara = $('#fileInputCamara')[0].files[0];
        var filePDF = $('#fileInputPDF')[0].files[0];

        if (fileCamara) {
            formData.append('archivo_pdf', fileCamara);
        } else if (filePDF) {
            formData.append('archivo_pdf', filePDF);
        }

        $.ajax({
            url: 'api/guardar.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (res) {
                if (res.status === 'success') {
                    alert(res.message);
                    $('#formDocumento')[0].reset();
                    $('#file-preview').hide();
                    $('#sec-registro').slideUp();
                    cargarDocumentos();
                } else {
                    alert('Error: ' + res.message);
                }
            }
        });
    });

    // 4. Abrir modal de estatus
    $(document).on('click', '.btn-editar-estatus, .fa-pen-to-square', function () {
        var id = $(this).attr('data-id');
        var estatusActual = $(this).attr('data-estatus');

        if (id) {
            $('#edit_id').val(id);
            $('#edit_estatus').val(estatusActual);
            $('#modalEstatus').fadeIn();
        }
    });

    // 5. Guardar cambio de estatus vía AJAX
    $(document).on('submit', '#formActualizarEstatus', function (e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: 'api/actualizar_estatus.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (res) {
                if (res.status === 'success') {
                    $('#modalEstatus').fadeOut();
                    cargarDocumentos();
                } else {
                    alert('Error: ' + res.message);
                }
            },
            error: function() {
                alert('Error de conexión con el servidor.');
            }
        });
    });
});

// Función para solicitar los datos al backend mediante AJAX
function cargarDocumentos() {
    var q = $('#busqueda').val();
    var estatus = $('#filtroEstatus').val();
    var desde = $('#filtroDesde').val();
    var hasta = $('#filtroHasta').val();

    $.ajax({
        url: 'api/listar.php',
        type: 'GET',
        data: { 
            q: q, 
            estatus: estatus, 
            desde: desde, 
            hasta: hasta 
        },
        dataType: 'json',
        success: function (res) {
            var html = '';
            if (res.data && res.data.length > 0) {
                res.data.forEach(function (doc) {
                    var statusClass = 'status-' + doc.estatus.replace(' ', '_');
                    html += `<tr>
                        <td><strong>${doc.no_oficio}</strong></td>
                        <td>${doc.fecha}</td>
                        <td>${doc.asunto}</td>
                        <td>${doc.remitente}</td>
                        <td>${doc.destinatario}</td>
                        <td><span class="status-badge ${statusClass}">${doc.estatus}</span></td>
                        <td>
                            <div class="action-icons">
                                <a href="uploads/${doc.archivo_pdf}" target="_blank" title="Ver PDF"><i class="fa-regular fa-eye"></i></a>
                                <a href="uploads/${doc.archivo_pdf}" download title="Descargar"><i class="fa-solid fa-download"></i></a>
                                <i class="fa-solid fa-pen-to-square btn-editar-estatus" data-id="${doc.id}" data-estatus="${doc.estatus}" style="cursor:pointer;" title="Editar Estatus"></i>
                            </div>
                        </td>
                    </tr>`;
                });
            } else {
                html = '<tr><td colspan="7" style="text-align:center;">No se encontraron registros en el rango seleccionado.</td></tr>';
            }
            $('#tablaDocumentos').html(html);
        }
    });
}