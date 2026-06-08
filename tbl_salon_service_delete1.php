<?php
session_start();
require_once 'database.php';

// Only allow logged-in manager
if (empty($_SESSION['manager_id']) || empty($_SESSION['salon_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Not authorized']);
    exit;
}

// Must be POST with service_id
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['service_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit;
}

$salon_id   = intval($_SESSION['salon_id']);
$service_id = intval($_POST['service_id']);

$query = "DELETE FROM service WHERE service_id = ? AND salon_id = ?";
$stmt  = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'ii', $service_id, $salon_id);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} else {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error'   => 'خدمت یافت نشد یا دسترسی ندارید'
    ]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
exit;

?>