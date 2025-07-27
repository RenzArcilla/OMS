<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEPC - File Upload</title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/file_upload/file_upload.css">
</head>
<body>
<section class="upload-section" id="upload">
        <div class="container">
            <h2 class="section-title">File Upload & Management</h2>
            <div class="upload-container">
                <div class="upload-grid">
                    <div class="upload-area">
                        <div class="upload-box" id="uploadBox">
                            <div class="upload-icon">📁</div>
                            <h3>Drop files here or click to browse</h3>
                            <p>Supported formats: .csv</p>
                            <input type="file" id="fileInput" multiple accept=".csv" style="display: none;">
                            <div class="upload-btn">
                                <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click()">
                                    Choose Files
                                </button>
                            </div>
                        </div>
                        
                        <div class="upload-progress" id="uploadProgress" style="display: none;">
                            <div class="progress-bar">
                                <div class="progress-fill" id="progressFill"></div>
                            </div>
                            <span class="progress-text" id="progressText">0%</span>
                        </div>
                    </div>
                    
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
                
                <div class="recent-uploads">
                    <h3>Recent Uploads</h3>
                    <div class="upload-list" id="uploadList">
                        <div class="upload-item">
                            <div class="file-icon">📊</div>
                            <div class="file-info">
                                <div class="file-name">sample1.csv</div>
                                <div class="file-details">Size count KB • Must include time here</div>
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
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>