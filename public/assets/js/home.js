function updateDashboardTime() {
    const now = new Date();
    // Format as HH:MM:SS
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    // Optionally, show timezone
    const tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
    document.getElementById('dashboardTime').textContent = `${hours}:${minutes}:${seconds} (${tz})`;
}
updateDashboardTime();
setInterval(updateDashboardTime, 1000);