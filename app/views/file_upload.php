<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/assets/css/base/header.css">
    <link rel="icon" href="/SOMS/public/assets/images/favicon.ico">
    <link rel="stylesheet" href="../../public/assets/css/base/typography.css">
    <link rel="stylesheet" href="../../public/assets/css/file_upload.css">
    <link rel="stylesheet" href="../../public/assets/css/components/cards.css">
    <title>SOMS - File Upload </title>
</head>
<body>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/SOMS/app/includes/header.php'; ?>
        <div class="container">
            <div class="card-title">
                <h1>From NOAH to SOMS</h1>
                <p class="subtitle">Upload your files here</p>
            </div>
            <div class="upload-container">
                <div class="upload-grid">
                    <div class="upload-area">
                        <form id="uploadForm" action="/SOMS/app/controllers/upload.php" method="POST" enctype="multipart/form-data">
                            <div class="upload-box" id="uploadBox">
                                <div class="upload-icon">📁</div>
                                <h3>Drop files here or click to browse</h3>
                                <p>Supported formats: .csv, .xls, .xlsx</p>
                                <input type="file" name="dataFiles[]" id="fileInput" multiple accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" style="display: none;">
                                <div class="upload-btn">
                                    <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click()">
                                        Choose Files
                                    </button>
                                    <button type="submit" class="btn btn-secondary">Upload</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card">
                        <div class="upload-info">
                            <h3>Upload Guidelines</h3>
                            <ul>
                                <li>Maximum file size: 100MB</li>
                                <li>Files are automatically scanned for security</li>
                                <li>Processed files are stored in secure server storage</li>
                                <li>All uploads are logged and tracked</li>
                            </ul>
                            
                            <div class="upload-stats">
                                <div class="stat-item">
                                    <div class="stat-value">1.2GB</div>
                                    <div class="stat-label">Total Storage Used</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">156</div>
                                    <div class="stat-label">Files Processed</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="recent-uploads">
                        <h3>Recent Uploads</h3>
                        <div class="upload-list" id="uploadList">
                            <div class="upload-item">
                                <div class="file-icon">📊</div>
                                <div class="file-info">
                                    <div class="file-name">sample1.csv</div>
                                    <div class="file-details">1.8 MB • Uploaded 2 minutes ago</div>
                                </div>
                                <div class="file-status success">✓ Processed</div>
                            </div>
                            <div class="upload-item">
                                <div class="file-icon">📊</div>
                                <div class="file-info">
                                    <div class="file-name">sample2.csv</div>
                                    <div class="file-details">2.4 MB • Uploaded 5 minutes ago</div>
                                </div>
                                <div class="file-status success">✓ Processed</div>
                            </div>
                            <div class="upload-item">
                                <div class="file-icon">📊</div>
                                <div class="file-info">
                                    <div class="file-name">analytics_data.csv</div>
                                    <div class="file-details">5.2 MB • Uploaded 1 hour ago</div>
                                </div>
                                <div class="file-status success">✓ Processed</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>