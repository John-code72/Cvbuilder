<!-- resources/views/pdf/view.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afficher le CV PDF</title>

    <!-- Inclure PDF.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.0.279/pdf.min.js"></script>

    <style>
        /* Ajoutons un peu de style pour le conteneur du PDF */
        #pdf-container {
            width: 100%;
            height: 600px;
            overflow: auto;
        }
        #pdf-render {
            width: 100%;
        }
    </style>
</head>
<body>

    <h1>Voici votre CV en PDF</h1>

    <div id="pdf-container">
        <canvas id="pdf-render"></canvas>
    </div>

    <script>
        // Définir l'URL du fichier PDF
        const pdfUrl = '{{ asset('cv.pdf') }}'; // PDF généré précédemment par DomPDF

        // Charger le PDF avec PDF.js
        const loadingTask = pdfjsLib.getDocument(pdfUrl);
        loadingTask.promise.then(function(pdf) {
            console.log('PDF chargé');
            
            // Afficher la première page du PDF
            pdf.getPage(1).then(function(page) {
                const scale = 1.5; // Ajuster l'échelle pour zoomer sur la page
                const viewport = page.getViewport({ scale: scale });

                // Préparer le canvas pour le rendu
                const canvas = document.getElementById('pdf-render');
                const context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                // Rendre la page sur le canvas
                const renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };
                page.render(renderContext);
            });
        });
    </script>

</body>
</html>
