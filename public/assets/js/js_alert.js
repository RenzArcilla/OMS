function closeJsAlertModal() {
    document.getElementById('jsAlertModalOverlay').remove();
    window.location.href = '$redirect_url';
}
// Allow pressing Enter to close modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') closeJsAlertModal();
});
