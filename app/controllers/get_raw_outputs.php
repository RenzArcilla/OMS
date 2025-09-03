<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

try {
	// Optional: filter by applicator_id
	$applicator_id = $_GET['applicator_id'] ?? null;

	if ($applicator_id) {
		$sql = "SELECT * FROM applicator_outputs WHERE applicator_id = :applicator_id AND is_active = 1";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':applicator_id', $applicator_id, PDO::PARAM_INT);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($row) {
			echo json_encode(['success' => true, 'data' => $row]);
		} else {
			echo json_encode(['success' => false, 'message' => 'No active outputs found for the specified applicator.']);
		}
	} else {
		$sql = "SELECT * FROM applicator_outputs WHERE is_active = 1";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

		echo json_encode(['success' => true, 'data' => $rows]);
	}
} catch (Exception $e) {
	echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>


