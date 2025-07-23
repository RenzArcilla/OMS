<?php
function getMachines($pdo, $limit = 10, $offset = 0) {
    $stmt = $pdo->prepare("SELECT machine_id, control_no, description, model, maker, serial_no, invoice_no 
                           FROM machines 
                           ORDER BY machine_id DESC 
                           LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}