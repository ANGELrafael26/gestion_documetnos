<?php
header('Content-Type: application/json');
require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $no_oficio    = trim($_POST['no_oficio'] ?? '');
    $fecha        = trim($_POST['fecha'] ?? '');
    $asunto       = trim($_POST['asunto'] ?? '');
    $remitente    = trim($_POST['remitente'] ?? '');
    $destinatario = trim($_POST['destinatario'] ?? '');
    $estatus      = trim($_POST['estatus'] ?? 'Recibido');

    if (empty($no_oficio) || empty($fecha) || empty($asunto) || empty($remitente) || empty($destinatario)) {
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios.']);
        exit;
    }

    if (isset($_FILES['archivo_pdf']) && $_FILES['archivo_pdf']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath   = $_FILES['archivo_pdf']['tmp_name'];
        $fileName      = $_FILES['archivo_pdf']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
        if (!in_array($fileExtension, $allowedExtensions)) {
            echo json_encode(['status' => 'error', 'message' => 'Formato no permitido. Solo PDF e imágenes.']);
            exit;
        }

        $newFileName   = time() . '_' . preg_replace("/[^a-zA-Z0-9\.]/", "_", $fileName);
        $uploadFileDir = __DIR__ . '/../uploads/';

        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0777, true);
        }

        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $stmt = $pdo->prepare("INSERT INTO documentos (no_oficio, fecha, asunto, remitente, destinatario, estatus, archivo_pdf) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$no_oficio, $fecha, $asunto, $remitente, $destinatario, $estatus, $newFileName]);

            echo json_encode(['status' => 'success', 'message' => 'Documento registrado exitosamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo mover el archivo. Verifica los permisos de la carpeta uploads.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Debe adjuntar un archivo válido.']);
    }
}
?>