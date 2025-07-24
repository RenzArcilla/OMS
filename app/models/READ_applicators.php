<?php
/*
    This file defines a function that queries a list of applicators from the database.
    Used in the applicator listing with pagination, such as in infinite scroll.
*/


function getApplicators(PDO $pdo, int $limit = 10, int $offset = 0): array {
    /*
    Retrieve applicator records from the database with pagination.

    This function fetches applicator rows ordered by latest ID, limited by
    the given amount and offset. Used for infinite scroll in the frontend.

    Args:
    - $pdo (PDO): PDO database connection object.
    - $limit (int): Number of rows to fetch.
    - $offset (int): Number of rows to skip.

    Returns:
    - array: Associative array of applicator records.
    */


    $stmt = $pdo->prepare("SELECT hp_no, terminal_no, description, wire, terminal_maker, applicator_maker, serial_no, invoice_no
                           FROM applicators
                           ORDER BY applicator_id DESC
                           LIMIT :limit OFFSET :offset");

    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
