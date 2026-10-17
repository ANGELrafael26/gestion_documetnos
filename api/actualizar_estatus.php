<?php
header('Content-Type: application/json');
require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id      = intval($_POST['id'] ?? 0);
    $estatus = trim($_POST['estatus'] ?? '');

    $estatusPermitidos = ['Recibido', 'En proceso', 'Atendido'];

    if ($id <= 0 || !in_array($estatus, $estatusPermitidos)) {
        echo json_encode(['status' => 'error', 'message' => 'Datos inválidos.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE documentos SET estatus = ? WHERE id = ?");
        $stmt->execute([$estatus, $id]);

        echo json_encode(['status' => 'success', 'message' => 'Estatus actualizado correctamente.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el estatus.']);
    }
}
?>