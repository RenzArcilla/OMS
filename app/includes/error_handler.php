<?php
// Includes fatal error handling functions
require_once __DIR__ . '/js_alert.php';


// Register a global shutdown function to handle fatal errors gracefully
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
        error_log("Fatal error: " . $error['message'] . " in " . $error['file'] . " line " . $error['line']);

        // You can customize this message for exports only if needed
        if (php_sapi_name() !== 'cli') {
            jsAlertRedirect("Export failed due to system limits. Please try again with a smaller date range.", '../views/home.php');
            exit;
        }
    }
});
