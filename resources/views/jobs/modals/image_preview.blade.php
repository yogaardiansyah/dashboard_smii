<!-- Image Preview Lightbox Modal -->
<div id="imagePreviewModal" class="image-preview-overlay" style="display: none;" aria-hidden="true" role="dialog" aria-modal="true">
    <div class="image-preview-backdrop"></div>
    <div class="image-preview-dialog">
        <!-- Header Toolbar -->
        <div class="image-preview-header">
            <div class="image-preview-title-wrap">
                <i class="fa-solid fa-image text-blue-400 mr-2.5 text-base flex-shrink-0"></i>
                <span id="imagePreviewTitle" class="image-preview-title truncate">Image Preview</span>
            </div>
            <div class="image-preview-actions">
                <a id="imagePreviewDownloadBtn" href="#" download class="image-preview-btn" title="Download Image">
                    <i class="fa-solid fa-download mr-1.5"></i>
                    <span class="hidden sm:inline">Download</span>
                </a>
                <a id="imagePreviewNewTabBtn" href="#" target="_blank" rel="noopener noreferrer" class="image-preview-btn" title="Open Original in New Tab">
                    <i class="fa-solid fa-arrow-up-right-from-square mr-1.5"></i>
                    <span class="hidden sm:inline">Open Original</span>
                </a>
                <button type="button" class="image-preview-btn image-preview-close-btn" id="imagePreviewCloseBtn" title="Close Preview (Esc)">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Image Body -->
        <div class="image-preview-body">
            <div id="imagePreviewSpinner" class="image-preview-spinner">
                <i class="fa-solid fa-spinner fa-spin text-3xl text-blue-400 mr-3"></i>
                <span class="text-sm font-medium text-slate-300">Loading image...</span>
            </div>
            <img id="imagePreviewImg" src="" alt="Image Preview" class="image-preview-img" style="display: none;">
        </div>
    </div>
</div>

<style>
    .image-preview-overlay {
        position: fixed;
        inset: 0;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100vw;
        height: 100vh;
        z-index: 1045 !important; /* Above .pl-modal-overlay (1040), below SweetAlert (1060+) */
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        box-sizing: border-box;
    }

    .image-preview-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        cursor: pointer;
    }

    .image-preview-dialog {
        position: relative;
        z-index: 1;
        max-width: 95vw;
        max-height: 94vh;
        display: flex;
        flex-direction: column;
        border-radius: 16px;
        background: #0f172a;
        border: 1px solid rgba(255, 255, 255, 0.15);
        box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.7);
        overflow: hidden;
        animation: imgPreviewZoomIn 0.22s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    @keyframes imgPreviewZoomIn {
        from {
            opacity: 0;
            transform: scale(0.92);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .image-preview-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 18px;
        background: rgba(15, 23, 42, 0.95);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        gap: 12px;
        flex-shrink: 0;
    }

    .image-preview-title-wrap {
        display: flex;
        align-items: center;
        min-width: 0;
        flex: 1;
    }

    .image-preview-title {
        color: #f8fafc;
        font-weight: 600;
        font-size: 14px;
        max-width: 480px;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
    }

    .image-preview-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    .image-preview-btn {
        padding: 6px 12px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: #e2e8f0;
        font-size: 13px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        transition: all 0.15s ease;
        cursor: pointer;
        line-height: 1.4;
    }

    .image-preview-btn:hover {
        background: rgba(255, 255, 255, 0.18);
        color: #ffffff;
        border-color: rgba(255, 255, 255, 0.3);
    }

    .image-preview-close-btn {
        background: rgba(239, 68, 68, 0.15);
        border-color: rgba(239, 68, 68, 0.3);
        color: #fca5a5;
        padding: 6px 10px;
    }

    .image-preview-close-btn:hover {
        background: #ef4444;
        border-color: #ef4444;
        color: #ffffff;
    }

    .image-preview-body {
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: auto;
        min-height: 220px;
        max-height: calc(94vh - 65px);
        background: radial-gradient(circle, rgba(30, 41, 59, 0.7) 0%, rgba(15, 23, 42, 0.98) 100%);
    }

    .image-preview-img {
        max-width: 90vw;
        max-height: calc(90vh - 90px);
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    .image-preview-spinner {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
    }
</style>

<script>
    (function() {
        window.openImagePreview = function(url, title) {
            const modal = document.getElementById('imagePreviewModal');
            const img = document.getElementById('imagePreviewImg');
            const spinner = document.getElementById('imagePreviewSpinner');
            const titleEl = document.getElementById('imagePreviewTitle');
            const downloadBtn = document.getElementById('imagePreviewDownloadBtn');
            const newTabBtn = document.getElementById('imagePreviewNewTabBtn');

            if (!modal || !img) return;

            const filename = title || 'Image Preview';
            if (titleEl) titleEl.textContent = filename;
            if (downloadBtn) {
                downloadBtn.href = url;
                downloadBtn.setAttribute('download', filename);
            }
            if (newTabBtn) {
                newTabBtn.href = url;
            }

            img.style.display = 'none';
            if (spinner) spinner.style.display = 'flex';

            img.onload = function() {
                if (spinner) spinner.style.display = 'none';
                img.style.display = 'block';
            };
            img.onerror = function() {
                if (spinner) spinner.style.display = 'none';
                if (titleEl) titleEl.textContent = 'Failed to load image';
            };

            if (modal.parentElement && modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }
            img.src = url;
            modal.style.display = 'flex';
        };

        window.closeImagePreview = function() {
            const modal = document.getElementById('imagePreviewModal');
            const img = document.getElementById('imagePreviewImg');
            if (modal) modal.style.display = 'none';
            if (img) img.src = '';
        };

        // Universal click delegation for preview image buttons
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.preview-img-btn, [data-preview-img]');
            if (btn) {
                e.preventDefault();
                e.stopPropagation();
                const url = btn.getAttribute('data-img-url') || btn.getAttribute('data-preview-img');
                const name = btn.getAttribute('data-img-name') || btn.getAttribute('data-filename') || 'Image Preview';
                if (url) {
                    window.openImagePreview(url, name);
                }
                return;
            }

            // Close button or backdrop click
            if (e.target.closest('#imagePreviewCloseBtn') || (e.target.classList && e.target.classList.contains('image-preview-backdrop'))) {
                e.preventDefault();
                window.closeImagePreview();
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('imagePreviewModal');
                if (modal && modal.style.display !== 'none') {
                    e.preventDefault();
                    e.stopPropagation();
                    window.closeImagePreview();
                }
            }
        });
    })();
</script>
