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
    background: linear-gradient(135deg, rgba(220, 20, 60, 0.7) 0%, rgba(139, 0, 0, 0.8) 50%, rgba(178, 34, 34, 0.7) 100%); 
    z-index: 9999; display: flex; align-items: center; justify-content: center;
}
.js-alert-modal {
    background: rgba(255, 255, 255, 0.95); padding: 2em 2.5em; border-radius: 20px; 
    box-shadow: 0 25px 50px rgba(220, 20, 60, 0.3), 0 10px 30px rgba(139, 0, 0, 0.2);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; text-align: center; min-width: 300px;
    backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1);
    color: #374151;
}
.js-alert-modal button {
    margin-top: 1.5em; padding: 0.5em 2em; border: none; border-radius: 12px;
    background: linear-gradient(135deg, #DC143C, #B22222); color: #fff; font-size: 1em; cursor: pointer;
    font-weight: 600; box-shadow: 0 4px 12px rgba(220, 20, 60, 0.25);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.js-alert-modal button:hover {
    background: linear-gradient(135deg, #B22222, #8B0000); transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(220, 20, 60, 0.35);
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