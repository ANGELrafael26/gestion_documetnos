<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNACH - Gestión de Documentos</title>
    
    <!-- 1. Bootstrap 5 CSS (Agregado aquí) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Iconos FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Estilos Propios (Se mantiene al final para preservar el diseño intacto) -->
    <link rel="stylesheet" href="css/estilos.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="app-container">
    <!-- Sidebar / Menú Lateral -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <i class="fa-solid fa-file-invoice logo-icon"></i>
            <div>
                <h2>UNACH</h2>
                <span>Universidad Autónoma de Chiapas</span>
            </div>
        </div>
        <nav class="sidebar-nav">
            <a href="#" class="nav-item"><i class="fa-solid fa-house"></i> Inicio</a>
            <a href="#" class="nav-item active"><i class="fa-solid fa-file-lines"></i> Documentos</a>
            <a href="#" class="nav-item" id="btn-toggle-nuevo" onclick="$('#sec-registro').slideToggle()"><i class="fa-solid fa-square-plus"></i> Nuevo Documento</a>
            <a href="#" class="nav-item"><i class="fa-solid fa-magnifying-glass"></i> Buscar</a>
            <a href="#" class="nav-item"><i class="fa-solid fa-chart-bar"></i> Reportes</a>
            <a href="#" class="nav-item"><i class="fa-solid fa-users"></i> Usuarios</a>
            <a href="#" class="nav-item"><i class="fa-solid fa-gear"></i> Configuración</a>
        </nav>
    </aside>

    <!-- Contenido Principal -->
    <main class="main-content">
        <!-- Header Superior -->
        <header class="topbar">
            <div class="topbar-title">
                <h1>Gestión de Documentos</h1>
                <small>Oficina Digital</small>
            </div>
            <div class="topbar-user">
                <i class="fa-regular fa-bell icon-btn"></i>
                <div class="user-info">
                    <i class="fa-solid fa-circle-user avatar"></i>
                    <div>
                        <strong>Administrador</strong>
                        <small>ETDA - Campus IV</small>
                    </div>
                </div>
            </div>
        </header>

        <div class="content-body">

            <!-- Formulario de Registro -->
            <section class="card-box" id="sec-registro" style="display: none;">
                <div class="card-title">
                    <h3><i class="fa-solid fa-file-signature"></i> Registrar Documento</h3>
                </div>
                <form id="formDocumento" enctype="multipart/form-data">
                    <div class="form-row gap-15">
                        <div class="form-group flex-1">
                            <label>No. de Oficio *</label>
                            <input type="text" name="no_oficio" required placeholder="Ej. 0452/2026">
                        </div>
                        <div class="form-group flex-1">
                            <label>Fecha *</label>
                            <input type="date" name="fecha" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Asunto *</label>
                        <input type="text" name="asunto" required placeholder="Descripción breve del asunto del documento">
                    </div>

                    <div class="form-row gap-15">
                        <div class="form-group flex-1">
                            <label>Remitente *</label>
                            <input type="text" name="remitente" required placeholder="Nombre o dependencia">
                        </div>
                        <div class="form-group flex-1">
                            <label>Destinatario *</label>
                            <input type="text" name="destinatario" required placeholder="Nombre o área">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Estatus *</label>
                        <select name="estatus" id="estatus">
                            <option value="Recibido">🟢 Recibido</option>
                            <option value="En proceso">🟡 En proceso</option>
                            <option value="Atendido">🔵 Atendido</option>
                        </select>
                    </div>

                    <label>Documento (Foto o PDF) *</label>
                    <div class="upload-options-grid">
                        <div class="upload-btn-card" onclick="$('#fileInputCamara').click()">
                            <i class="fa-solid fa-camera upload-icon"></i>
                            <span>Tomar foto del documento</span>
                        </div>
                        <div class="upload-btn-card" onclick="$('#fileInputPDF').click()">
                            <i class="fa-solid fa-cloud-arrow-up upload-icon"></i>
                            <span>Seleccionar archivo PDF</span>
                        </div>
                    </div>

                    <input type="file" id="fileInputCamara" name="archivo_pdf_cam" accept="image/*" capture="environment" style="display:none">
                    <input type="file" id="fileInputPDF" name="archivo_pdf_file" accept="application/pdf" style="display:none">

                    <div id="file-preview" class="file-preview-box" style="display: none;">
                        <i class="fa-solid fa-file-pdf pdf-red"></i>
                        <span id="file-name">Oficio_0452_2026.pdf</span>
                        <i class="fa-solid fa-xmark remove-file" id="btn-remove-file"></i>
                    </div>

                    <button type="submit" class="btn-primary btn-block margin-top-15">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar Documento
                    </button>
                </form>
            </section>

            <!-- Listado y Búsqueda -->
            <section class="card-box">
                <div class="card-header-flex">
                    <h3>Listado de Documentos</h3>
                    <button class="btn-primary" onclick="$('#sec-registro').slideToggle()">
                        <i class="fa-solid fa-plus"></i> Nuevo Documento
                    </button>
                </div>

                <!-- Buscador y Filtros Corregidos -->
<div class="filter-wrapper">
    <div class="search-input-box">
        <i class="fa-solid fa-magnifying-glass search-icon"></i>
        <input type="text" id="busqueda" placeholder="Buscar por No. de oficio, asunto, remitente o destinatario...">
    </div>

    <div class="filters-row">
        <div class="filter-group">
            <label>Estatus</label>
            <select id="filtroEstatus">
                <option value="Todos">Todos</option>
                <option value="Recibido">Recibido</option>
                <option value="En proceso">En proceso</option>
                <option value="Atendido">Atendido</option>
            </select>
        </div>
        <div class="filter-group">
            <label>Desde</label>
            <input type="date" id="filtroDesde">
        </div>
        <div class="filter-group">
            <label>Hasta</label>
            <input type="date" id="filtroHasta">
        </div>
        <button type="button" id="btnFiltrar" class="btn-primary" style="align-self: flex-end; padding: 10px 22px;">
            <i class="fa-solid fa-filter"></i> Filtrar
        </button>
    </div>
</div>

                <!-- Tabla de Documentos -->
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>No. Oficio</th>
                                <th>Fecha</th>
                                <th>Asunto</th>
                                <th>Remitente</th>
                                <th>Destinatario</th>
                                <th>Estatus</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaDocumentos">
                            <!-- Filas cargadas por AJAX/jQuery -->
                        </tbody>
                    </table>
                </div>

                <!-- Paginador -->
                <div class="pagination-footer">
                    <span>Mostrando 1 a 5 de 28 registros</span>
                    <div class="pagination-buttons">
                        <button class="page-btn">&laquo;</button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-btn">&raquo;</button>
                    </div>
                </div>
            </section>

        </div>
    </main>
</div>

<!-- Modal para cambiar estatus con desplegable -->
<div id="modalEstatus" class="modal-overlay" style="display: none;">
    <div class="modal-card">
        <h3>Actualizar Estatus</h3>
        <form id="formActualizarEstatus">
            <input type="hidden" id="edit_id" name="id">
            
            <div class="form-group" style="margin: 20px 0;">
                <label style="display:block; margin-bottom: 8px; font-weight: bold;">Selecciona el estatus:</label>
                <select id="edit_estatus" name="estatus" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 14px;">
                    <option value="Recibido">🟢 Recibido</option>
                    <option value="En proceso">🟡 En proceso</option>
                    <option value="Atendido">🔵 Atendido</option>
                </select>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" class="btn-secondary" onclick="$('#modalEstatus').fadeOut()">Cancelar</button>
                <button type="submit" class="btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- 2. Bootstrap 5 JavaScript Bundle (Agregado al final) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="js/app.js"></script>

</body>
</html>