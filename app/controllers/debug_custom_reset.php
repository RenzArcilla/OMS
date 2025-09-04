<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';

try {
    $machine_id = 1; // CTRL001
    $part_name = 'cooling_fan';
    
    echo "=== DEBUGGING CUSTOM PART RESET ===\n";
    echo "Machine ID: $machine_id\n";
    echo "Part Name: $part_name\n\n";
    
    // 1. Check current database state
    $sql = "SELECT machine_id, control_no, custom_parts_output FROM monitor_machine WHERE machine_id = :machine_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':machine_id', $machine_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Current database state:\n";
    echo "Machine ID: " . $row['machine_id'] . "\n";
    echo "Control No: " . $row['control_no'] . "\n";
    echo "Custom Parts Output: " . $row['custom_parts_output'] . "\n\n";
    
    // 2. Decode the JSON
    $decoded = json_decode($row['custom_parts_output'], true);
    echo "Decoded JSON:\n";
    print_r($decoded);
    echo "\n";
    
    // 3. Check current value
    echo "Current value for '$part_name': " . ($decoded[$part_name] ?? 'NOT FOUND') . "\n\n";
    
    // 4. Test the reset function
    require_once __DIR__ . '/../models/update_monitor_machine.php';
    echo "Calling resetMachinePartOutput...\n";
    $result = resetMachinePartOutput($machine_id, $part_name);
    echo "Reset result: " . ($result === true ? 'SUCCESS' : $result) . "\n\n";
    
    // 5. Check database state after reset
    $stmt->execute();
    $row_after = $stmt->fetch(PDO::FETCH_ASSOC);
    $decoded_after = json_decode($row_after['custom_parts_output'], true);
    
    echo "After reset:\n";
    echo "Custom Parts Output: " . $row_after['custom_parts_output'] . "\n";
    echo "Decoded JSON:\n";
    print_r($decoded_after);
    echo "\n";
    echo "Value for '$part_name' after reset: " . ($decoded_after[$part_name] ?? 'NOT FOUND') . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
