
<!-- File Upload Section - Scoped to prevent layout conflicts -->
<div class="file-upload-section">
    <div class="upload-container">
        <div class="upload-grid">
            <div class="upload-area">
                <form id="uploadForm" action="/OMS/app/controllers/upload.php" method="POST" enctype="multipart/form-data">
                    <div class="upload-box" id="uploadBox">
                        <div class="upload-icon">📁</div>
                        <h3>Drop files here or click to browse</h3>
                        <p>Supported formats: .csv, .xls, .xlsx</p>
                        <input type="file" name="dataFiles[]" id="fileInput" multiple accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" style="display: none;">
                        <div class="button-group">
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
                        <li>Maximum file size: 10MB</li>
                        <li>STRICTLY! One file at a time</li>
                        <li>Files are automatically scanned for security</li>
                        <li>Processed files are stored in secure server storage</li>
                        <li>All uploads are logged and tracked</li>
                </div>
            </div>
        </div>
        <!--div class="recent-uploads">
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
        </div-->
    </div>
</div>