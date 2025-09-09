function updateDashboardTime() {
    const now = new Date();
    
    // Get Philippine time
    const phTime = new Date(now.toLocaleString("en-US", {timeZone: "Asia/Manila"}));
    
    const hours = String(phTime.getHours()).padStart(2, '0');
    const minutes = String(phTime.getMinutes()).padStart(2, '0');
    const seconds = String(phTime.getSeconds()).padStart(2, '0');
    
    document.getElementById('dashboardTime').textContent = `${hours}:${minutes}:${seconds} (Philippine Time)`;
}
updateDashboardTime();
setInterval(updateDashboardTime, 1000);