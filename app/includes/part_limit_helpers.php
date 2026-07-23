<?php

function buildApplicatorLimitsMap(array $rows): array {
    $map = [];
    foreach ($rows as $row) {
        $map[(int) $row['applicator_id'] . '|' . $row['applicator_part']] = (int) $row['part_limit'];
    }
    return $map;
}

function getApplicatorPartLimit($applicatorId, string $part, array $limitsMap, int $default = 500000): int {
    return $limitsMap[(int) $applicatorId . '|' . $part] ?? $default;
}

function formatPartLimitLabel(int $limit): string {
    if ($limit >= 1000000) {
        $millions = $limit / 1000000;
        if ($limit % 1000000 === 0) {
            return ((int) $millions) . 'M';
        }
        return rtrim(rtrim(number_format($millions, 1), '0'), '.') . 'M';
    }

    return ($limit / 1000) . 'K';
}

function isAjaxRequest(): bool {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

function respondWithResult(string $message, string $redirectUrl, bool $success = true): void {
    if (isAjaxRequest()) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'message' => $message,
        ]);
        exit;
    }

    require_once __DIR__ . '/js_alert.php';
    jsAlertRedirect($message, $redirectUrl);
    exit;
}
