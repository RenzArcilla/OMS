<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        throw new Exception('Invalid JSON input');
    }
    
    $applicator_id = $input['applicator_id'] ?? null;
    $updates = $input['updates'] ?? [];
    
    if (!$applicator_id) {
        throw new Exception('Applicator ID is required');
    }
    
    if (empty($updates)) {
        throw new Exception('No updates provided');
    }
    
    // Build dynamic UPDATE query
    $set_clauses = [];
    $params = [];
    
    foreach ($updates as $field => $value) {
        // Validate field names to prevent SQL injection
        $allowed_fields = [
            'wire_crimper', 'wire_anvil', 'insulation_crimper', 'insulation_anvil',
            'slide_cutter', 'cutter_holder', 'shear_blade', 'cutter_a', 'cutter_b',
            'custom_parts', 'total_output'
        ];
        
        if (in_array($field, $allowed_fields)) {
            $set_clauses[] = "$field = :$field";
            $params[":$field"] = $value;
        }
    }
    
    if (empty($set_clauses)) {
        throw new Exception('No valid fields to update');
    }
    
    // Add timestamp update
    $set_clauses[] = "last_updated = NOW()";
    
    $sql = "UPDATE applicator_outputs SET " . implode(', ', $set_clauses) . 
           " WHERE applicator_id = :applicator_id AND is_active = 1";
    
    $params[':applicator_id'] = $applicator_id;
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'success' => true, 
            'message' => 'Applicator outputs updated successfully',
            'rows_affected' => $stmt->rowCount()
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'No rows were updated. Applicator may not exist or be inactive.'
        ]);
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
