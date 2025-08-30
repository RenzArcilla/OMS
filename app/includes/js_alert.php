<?php
/*
    This file contains utility functions for the application.
*/

/**
 * Display a custom-styled alert using a modal and redirect after closing.
 * 
 * @param string $message The message to display in the alert.
 * @param string $redirect_url The URL to redirect to after the alert.
 * @param string $css Optional custom CSS for the modal (inline or <style> block).
 */
function jsAlertRedirect($message, $redirect_url = "../views/login.php", $css = "") {
    // Default CSS for the modal if none provided
    $defaultCss = <<<EOT

EOT;

    $cssBlock = $css ? $css : $defaultCss;
    $escapedMessage = htmlspecialchars($message, ENT_QUOTES);

    echo <<<EOD
$cssBlock
<div class="js-alert-modal-overlay" id="jsAlertModalOverlay">
    <div class="js-alert-modal">
        <div>$escapedMessage</div>
        <button onclick="closeJsAlertModal()">OK</button>
    </div>
</div>
<script src="../assets/js/js_alert.js"></script>
EOD;
}