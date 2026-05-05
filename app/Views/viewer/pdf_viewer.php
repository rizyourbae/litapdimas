<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> | Document Viewer</title>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- PDF.js from CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    
    <style>
        :root {
            --bg-color: #1a1a1a;
            --toolbar-bg: #2d2d2d;
            --text-color: #e0e0e0;
            --accent-color: #0d6efd;
        }

        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
            overflow: hidden;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
        }

        .viewer-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .viewer-header {
            background-color: var(--toolbar-bg);
            padding: 0.75rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
            z-index: 100;
        }

        .doc-title {
            font-weight: 600;
            font-size: 1rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 400px;
        }

        .viewer-main {
            flex: 1;
            overflow: auto;
            display: flex;
            justify-content: center;
            background-color: #333;
            padding: 2rem;
            position: relative;
        }

        #pdf-canvas-container {
            box-shadow: 0 0 30px rgba(0,0,0,0.5);
            background-color: white;
            position: relative;
        }

        .controls {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn-icon {
            background: none;
            border: none;
            color: var(--text-color);
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 6px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-icon:hover {
            background-color: rgba(255,255,255,0.1);
            color: var(--accent-color);
        }

        .btn-icon:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        .page-info {
            font-size: 0.9rem;
            background: rgba(0,0,0,0.2);
            padding: 0.4rem 0.8rem;
            border-radius: 4px;
        }

        /* Loading Spinner */
        .loader-wrapper {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(255,255,255,0.1);
            border-top: 5px solid var(--accent-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 8rem;
            color: rgba(0, 0, 0, 0.05);
            pointer-events: none;
            z-index: 10;
            user-select: none;
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .doc-title { max-width: 150px; }
            .viewer-main { padding: 0.5rem; }
        }
    </style>
</head>
<body>
    <div class="viewer-container">
        <header class="viewer-header">
            <div class="d-flex align-items-center">
                <button onclick="window.close()" class="btn-icon me-3" title="Tutup">
                    <i class="bi bi-x-lg"></i>
                </button>
                <div class="doc-title" id="doc-title-text"><?= esc($title) ?></div>
            </div>

            <div class="controls">
                <button id="prev-page" class="btn-icon" title="Halaman Sebelumnya">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <span class="page-info">
                    <span id="page-num">0</span> / <span id="page-count">0</span>
                </span>
                <button id="next-page" class="btn-icon" title="Halaman Selanjutnya">
                    <i class="bi bi-chevron-right"></i>
                </button>
                
                <div style="width: 1px; height: 24px; background: rgba(255,255,255,0.1); margin: 0 0.5rem;"></div>
                
                <button id="zoom-out" class="btn-icon" title="Zoom Out">
                    <i class="bi bi-zoom-out"></i>
                </button>
                <button id="zoom-in" class="btn-icon" title="Zoom In">
                    <i class="bi bi-zoom-in"></i>
                </button>
            </div>

            <div>
                <button onclick="window.print()" class="btn-icon" title="Cetak">
                    <i class="bi bi-printer"></i>
                </button>
            </div>
        </header>

        <main class="viewer-main" id="viewer-main">
            <div class="loader-wrapper" id="loader">
                <div class="spinner"></div>
                <div class="small">Memuat Dokumen...</div>
            </div>
            
            <div id="pdf-canvas-container">
                <!-- Watermark Layer -->
                <div class="watermark">SECURE VIEW</div>
                <canvas id="pdf-canvas"></canvas>
            </div>
        </main>
    </div>

    <script>
        const url = '<?= $fileUrl ?>';
        
        let pdfDoc = null,
            pageNum = 1,
            pageRendering = false,
            pageNumPending = null,
            scale = 1.5,
            canvas = document.getElementById('pdf-canvas'),
            ctx = canvas.getContext('2d');

        // Initialize PDF.js
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        /**
         * Get page info from document, resize canvas accordingly, and render page.
         * @param num Page number.
         */
        function renderPage(num) {
            pageRendering = true;
            // Using promise to fetch the page
            pdfDoc.getPage(num).then(function(page) {
                const viewport = page.getViewport({ scale: scale });
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                // Render PDF page into canvas context
                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };
                const renderTask = page.render(renderContext);

                // Wait for rendering to finish
                renderTask.promise.then(function() {
                    pageRendering = false;
                    if (pageNumPending !== null) {
                        // New page rendering is pending
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                    document.getElementById('loader').style.display = 'none';
                });
            });

            // Update page counters
            document.getElementById('page-num').textContent = num;
        }

        /**
         * If another page rendering in progress, waits until the rendering is
         * finised. Otherwise, executes rendering immediately.
         */
        function queueRenderPage(num) {
            if (pageRendering) {
                pageNumPending = num;
            } else {
                renderPage(num);
            }
        }

        /**
         * Displays previous page.
         */
        function onPrevPage() {
            if (pageNum <= 1) {
                return;
            }
            pageNum--;
            queueRenderPage(pageNum);
        }
        document.getElementById('prev-page').addEventListener('click', onPrevPage);

        /**
         * Displays next page.
         */
        function onNextPage() {
            if (pageNum >= pdfDoc.numPages) {
                return;
            }
            pageNum++;
            queueRenderPage(pageNum);
        }
        document.getElementById('next-page').addEventListener('click', onNextPage);

        /**
         * Zoom In
         */
        document.getElementById('zoom-in').addEventListener('click', () => {
            scale += 0.25;
            queueRenderPage(pageNum);
        });

        /**
         * Zoom Out
         */
        document.getElementById('zoom-out').addEventListener('click', () => {
            if (scale <= 0.5) return;
            scale -= 0.25;
            queueRenderPage(pageNum);
        });

        /**
         * Asynchronously downloads PDF.
         */
        pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
            pdfDoc = pdfDoc_;
            document.getElementById('page-count').textContent = pdfDoc.numPages;

            // Initial/first page rendering
            renderPage(pageNum);
        }).catch(function(error) {
            console.error('Error loading PDF:', error);
            document.getElementById('loader').innerHTML = '<div class="text-danger"><i class="bi bi-exclamation-triangle fs-1"></i><br>Gagal memuat dokumen.</div>';
        });
    </script>
</body>
</html>
