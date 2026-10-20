// Datos iniciales de demostración para Vercel
var documentosBD = [
    { id: 1, no_oficio: "001/2026", fecha: "2026-10-05", asunto: "Solicitud de materiales", remitente: "Dirección Administrativa", destinatario: "Coordinación Académica", estatus: "Recibido", archivo_pdf: "#" },
    { id: 2, no_oficio: "002/2026", fecha: "2026-10-04", asunto: "Invitación a evento", remitente: "Secretaría General", destinatario: "Dirección ETDA", estatus: "En proceso", archivo_pdf: "#" },
    { id: 3, no_oficio: "003/2026", fecha: "2026-10-03", asunto: "Reporte de actividades", remitente: "Departamento de Sistemas", destinatario: "Dirección ETDA", estatus: "Atendido", archivo_pdf: "#" },
    { id: 4, no_oficio: "004/2026", fecha: "2026-10-02", asunto: "Solicitud de apoyo", remitente: "Institución Externa", destinatario: "Coordinación de Investigación", estatus: "Recibido", archivo_pdf: "#" },
    { id: 5, no_oficio: "005/2026", fecha: "2026-10-01", asunto: "Oficio de colaboración", remitente: "C. Municipal de Tapachula", destinatario: "Rectoría", estatus: "En proceso", archivo_pdf: "#" }
];

$(document).ready(function () {
    // Carga inicial sin forzar filtro estricto de fecha
    cargarDocumentos(true);

    // Filtrar solo al presionar el botón Filtrar
    $('#btnFiltrar').on('click', function (e) {
        e.preventDefault();
        cargarDocumentos(false);
    });

    // Vista previa de archivo
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

    // Guardar nuevo documento
    $('#formDocumento').on('submit', function (e) {
        e.preventDefault();
        
        // Corregido operador OR (||)
        var fileCamara = $('#fileInputCamara')[0] ? $('#fileInputCamara')[0].files[0] : null;
        var filePDF = $('#fileInputPDF')[0] ? $('#fileInputPDF')[0].files[0] : null;
        var fileInput = fileCamara || filePDF;
        var urlArchivo = "#";

        if (fileInput) {
            urlArchivo = URL.createObjectURL(fileInput);
        }

        var nuevoDoc = {
            id: documentosBD.length + 1,
            no_oficio: $('input[name="no_oficio"]').val(),
            fecha: $('input[name="fecha"]').val() || new Date().toISOString().split('T')[0],
            asunto: $('input[name="asunto"]').val(),
            remitente: $('input[name="remitente"]').val(),
            destinatario: $('input[name="destinatario"]').val(),
            estatus: $('#estatus').val(),
            archivo_pdf: urlArchivo
        };

        documentosBD.unshift(nuevoDoc);
        alert('Documento registrado con éxito.');
        $('#formDocumento')[0].reset();
        $('#file-preview').hide();
        $('#sec-registro').slideUp();
        cargarDocumentos(true);
    });

    // Abrir Modal de Estatus
    $(document).on('click', '.btn-editar-estatus, .fa-pen-to-square', function () {
        var id = $(this).attr('data-id');
        var estatusActual = $(this).attr('data-estatus');

        $('#edit_id').val(id);
        $('#edit_estatus').val(estatusActual);
        $('#modalEstatus').fadeIn();
    });

    // Actualizar Estatus
    $(document).on('submit', '#formActualizarEstatus', function (e) {
        e.preventDefault();
        var id = $('#edit_id').val();
        var nuevoEstatus = $('#edit_estatus').val();

        for (var i = 0; i < documentosBD.length; i++) {
            if (documentosBD[i].id == id) {
                documentosBD[i].estatus = nuevoEstatus;
                break;
            }
        }

        $('#modalEstatus').fadeOut();
        cargarDocumentos(true);
    });
});

function cargarDocumentos(esCargaInicial) {
    var q = $('#busqueda').val() ? $('#busqueda').val().toLowerCase() : '';
    var estatus = $('#filtroEstatus').val();
    var desde = $('#filtroDesde').val();
    var hasta = $('#filtroHasta').val();

    var filtrados = documentosBD.filter(function(doc) {
        var cumpleQ = !q || doc.no_oficio.toLowerCase().includes(q) || doc.asunto.toLowerCase().includes(q) || doc.remitente.toLowerCase().includes(q) || doc.destinatario.toLowerCase().includes(q);
        var cumpleEstatus = (!estatus || estatus === 'Todos') || (doc.estatus === estatus);
        
        // Si es la carga inicial, ignorar el filtro automático de fechas para mostrar los registros de demostración
        var cumpleDesde = esCargaInicial || !desde || (doc.fecha >= desde);
        var cumpleHasta = esCargaInicial || !hasta || (doc.fecha <= hasta);

        return cumpleQ && cumpleEstatus && cumpleDesde && cumpleHasta;
    });

    var html = '';
    if (filtrados.length > 0) {
        filtrados.forEach(function (doc) {
            var statusClass = 'status-' + doc.estatus.replace(/\s+/g, '_');
            
            var tieneArchivo = doc.archivo_pdf !== '#';
            var enlaceVer = tieneArchivo ? doc.archivo_pdf : 'javascript:alert("Documento de prueba sin archivo subido.");';
            var enlaceDescargar = tieneArchivo ? doc.archivo_pdf : 'javascript:alert("Documento de prueba sin archivo subido.");';
            var targetAttr = tieneArchivo ? 'target="_blank"' : '';
            var downloadAttr = tieneArchivo ? `download="oficio_${doc.no_oficio.replace('/', '_')}"` : '';

            html += `<tr>
                <td><strong>${doc.no_oficio}</strong></td>
                <td>${doc.fecha}</td>
                <td>${doc.asunto}</td>
                <td>${doc.remitente}</td>
                <td>${doc.destinatario}</td>
                <td><span class="status-badge ${statusClass}">${doc.estatus}</span></td>
                <td>
                    <div class="action-icons">
                        <a href="${enlaceVer}" ${targetAttr} title="Ver PDF"><i class="fa-regular fa-eye"></i></a>
                        <a href="${enlaceDescargar}" ${downloadAttr} title="Descargar"><i class="fa-solid fa-download"></i></a>
                        <i class="fa-solid fa-pen-to-square btn-editar-estatus" data-id="${doc.id}" data-estatus="${doc.estatus}" style="cursor:pointer;" title="Editar Estatus"></i>
                    </div>
                </td>
            </tr>`;
        });
    } else {
        html = '<tr><td colspan="7" style="text-align:center;">No se encontraron registros en el rango seleccionado.</td></tr>';
    }
    
    // Asegurar que selector apunte a la etiqueta <tbody> de la tabla
    if ($('#tablaDocumentos tbody').length > 0) {
        $('#tablaDocumentos tbody').html(html);
    } else {
        $('#tablaDocumentos').html(html);
    }
}