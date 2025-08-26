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
<style>
.js-alert-modal-overlay {
    position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
    background: rgba(0,0,0,0.3); z-index: 9999; display: flex; align-items: center; justify-content: center;
}
.js-alert-modal {
    background: #fff; padding: 2em 2.5em; border-radius: 8px; box-shadow: 0 2px 16px rgba(0,0,0,0.2);
    font-family: Arial, sans-serif; text-align: center; min-width: 300px;
}
.js-alert-modal button {
    margin-top: 1.5em; padding: 0.5em 2em; border: none; border-radius: 4px;
    background: #007bff; color: #fff; font-size: 1em; cursor: pointer;
}
.js-alert-modal button:hover {
    background: #0056b3;
}
</style>
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
<script>
function closeJsAlertModal() {
    document.getElementById('jsAlertModalOverlay').remove();
    window.location.href = '$redirect_url';
}
// Allow pressing Enter to close modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') closeJsAlertModal();
});
</script>
EOD;
}