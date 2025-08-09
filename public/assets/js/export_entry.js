tableEl.querySelectorAll('tbody tr').forEach(tr => {
    const cells = Array.from(tr.querySelectorAll('td'));
    cells.pop(); // remove last cell (Actions)
    csvRows.push(cells.map(td => csvEscape(td.textContent.trim())).join(','));
  });

// Export currently visible table to CSV
function exportData() {
    // Determine active tab via active tab button text
    const activeTabBtn = document.querySelector('.tab-section .tab-btn.active');
    let targetId = 'machines-table';
    if (activeTabBtn && /applicators/i.test(activeTabBtn.textContent)) {
        targetId = 'applicators-table';
    }

    // Fallback to visible card if both buttons are not reliable
    const preferredCard = document.getElementById(targetId);
    let tableEl = preferredCard ? preferredCard.querySelector('table') : null;
    if (!tableEl) {
        const activeCard = document.querySelector('.entries-table-card.active');
        tableEl = activeCard ? activeCard.querySelector('table') : null;
    }
    if (!tableEl) {
        alert('No data table found to export.');
        return;
    }

    const datasetName = (targetId === 'applicators-table') ? 'applicators' : 'machines';

    const csvRows = [];
    // Headers
    const headerCells = tableEl.querySelectorAll('thead th');
    csvRows.push(Array.from(headerCells).map(th => csvEscape(th.textContent.trim())).join(','));

    // Body rows
    tableEl.querySelectorAll('tbody tr').forEach(tr => {
        const cells = tr.querySelectorAll('td');
        csvRows.push(Array.from(cells).map(td => csvEscape(td.textContent.trim())).join(','));
    });

    const csvContent = '\uFEFF' + csvRows.join('\r\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);

    const link = document.createElement('a');
    link.href = url;
    link.download = `${datasetName}_export_${formatTimestamp()}.csv`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
}

function csvEscape(value) {
    if (value == null) return '';
    const normalized = String(value).replace(/\r?\n|\r/g, ' ');
    if (/[",]/.test(normalized)) {
        return '"' + normalized.replace(/"/g, '""') + '"';
    }
    return normalized;
}

function formatTimestamp() {
    const d = new Date();
    const pad = n => String(n).padStart(2, '0');
    return `${d.getFullYear()}${pad(d.getMonth() + 1)}${pad(d.getDate())}_${pad(d.getHours())}${pad(d.getMinutes())}`;
}
