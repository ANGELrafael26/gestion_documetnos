<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../config/conexion.php';

try {
    $q = isset($_GET['q']) ? trim($_GET['q']) : '';
    $estatus = isset($_GET['estatus']) ? trim($_GET['estatus']) : 'Todos';
    $desde = isset($_GET['desde']) ? trim($_GET['desde']) : '';
    $hasta = isset($_GET['hasta']) ? trim($_GET['hasta']) : '';

    $sql = "SELECT * FROM documentos WHERE 1=1";
    $params = [];

    // Filtro por texto de búsqueda
    if (!empty($q)) {
        $sql .= " AND (no_oficio LIKE :q OR asunto LIKE :q OR remitente LIKE :q OR destinatario LIKE :q)";
        $params[':q'] = "%$q%";
    }

    // Filtro por Estatus
    if (!empty($estatus) && $estatus !== 'Todos') {
        $sql .= " AND estatus = :estatus";
        $params[':estatus'] = $estatus;
    }

    // Filtro por Fecha Desde
    if (!empty($desde)) {
        $sql .= " AND fecha >= :desde";
        $params[':desde'] = $desde;
    }

    // Filtro por Fecha Hasta
    if (!empty($hasta)) {
        $sql .= " AND fecha <= :hasta";
        $params[':hasta'] = $hasta;
    }

    $sql .= " ORDER BY id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => $data
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>