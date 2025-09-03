<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

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
        echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
        exit;
    }
    
    $machine_id = $input['machine_id'] ?? null;
    $updates = $input['updates'] ?? [];
    
    if (!$machine_id || !is_array($updates)) {
        echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
        exit;
    }
    
    // Validate machine exists
    $stmt = $pdo->prepare("SELECT machine_id FROM machines WHERE machine_id = ? AND is_active = 1");
    $stmt->execute([$machine_id]);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Machine not found or inactive']);
        exit;
    }
    
    // Define allowed fields for update
    $allowedFields = [
        'cut_blade_output',
        'strip_blade_a_output', 
        'strip_blade_b_output'
    ];
    
    // Get custom parts for machines
    require_once '../models/read_custom_parts.php';
    $customParts = getCustomParts('MACHINE');
    if (is_array($customParts)) {
        foreach ($customParts as $part) {
            $allowedFields[] = $part['part_name'];
        }
    }
    
    // Validate updates
    $validUpdates = [];
    foreach ($updates as $field => $value) {
        if (in_array($field, $allowedFields) && is_numeric($value)) {
            $validUpdates[$field] = (int)$value;
        }
    }
    
    if (empty($validUpdates)) {
        echo json_encode(['success' => false, 'message' => 'No valid updates provided']);
        exit;
    }
    
    // Update monitor_machine table
    $updateSuccess = updateMachineOutputs($machine_id, $validUpdates);
    
    if ($updateSuccess === true) {
        echo json_encode(['success' => true, 'message' => 'Machine outputs updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => $updateSuccess]);
    }
    
} catch (Exception $e) {
    error_log("Error in update_machine_outputs.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

function updateMachineOutputs($machine_id, $updates) {
    global $pdo;
    
    try {
        // Separate standard fields from custom parts
        $standardFields = [];
        $customParts = [];
        
        foreach ($updates as $field => $value) {
            if (in_array($field, ['cut_blade_output', 'strip_blade_a_output', 'strip_blade_b_output'])) {
                $standardFields[$field] = $value;
            } else {
                $customParts[$field] = $value;
            }
        }
        
        // Update standard fields
        if (!empty($standardFields)) {
            $setClause = [];
            $params = [];
            
            foreach ($standardFields as $field => $value) {
                $setClause[] = "$field = ?";
                $params[] = $value;
            }
            
            $params[] = $machine_id;
            
            $sql = "UPDATE monitor_machine SET " . implode(', ', $setClause) . " WHERE machine_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
        }
        
        // Update custom parts
        if (!empty($customParts)) {
            // Get current custom parts
            $stmt = $pdo->prepare("SELECT custom_parts_output FROM monitor_machine WHERE machine_id = ?");
            $stmt->execute([$machine_id]);
            $current = $stmt->fetchColumn();
            
            $currentCustomParts = [];
            if ($current) {
                $currentCustomParts = json_decode($current, true) ?: [];
            }
            
            // Merge with new values
            foreach ($customParts as $partName => $value) {
                $currentCustomParts[$partName] = $value;
            }
            
            // Update custom parts
            $customPartsJson = json_encode($currentCustomParts);
            $stmt = $pdo->prepare("UPDATE monitor_machine SET custom_parts_output = ? WHERE machine_id = ?");
            $stmt->execute([$customPartsJson, $machine_id]);
        }
        
        return true;
        
    } catch (PDOException $e) {
        error_log("Database error in updateMachineOutputs: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    }
}
?>
