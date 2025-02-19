async function fetchFiles() {
    try {
        const response = await fetch('downloadpdf.php');
        if (!response.ok) {
            throw new Error("Erro na resposta da requisição");
        }
        const files = await response.json();

        const fileList = document.getElementById('fileList');
        fileList.innerHTML = '';

        files.forEach(file => {
            const listItem = document.createElement('li');
            listItem.classList.add("file-item");


            const previewContainer = document.createElement('div');
            previewContainer.classList.add("preview");


            const canvas = document.createElement('canvas');
            canvas.classList.add("preview-canvas");
            const context = canvas.getContext('2d');


            const loadingTask = pdfjsLib.getDocument(file.url);
            loadingTask.promise.then(pdf => {
                pdf.getPage(1).then(page => {
                    const scale = 0.35;
                    const viewport = page.getViewport({ scale });


                    canvas.width = viewport.width;
                    canvas.height = viewport.height;

                    page.render({ canvasContext: context, viewport: viewport }).promise.then(() => {
                        console.log("Pré-visualização do PDF gerada.");
                    });
                });
            }).catch(error => {
                console.error("Erro ao carregar PDF:", error);
            });

            previewContainer.appendChild(canvas);
            listItem.appendChild(previewContainer);


            const assunto = document.createElement('p');
            assunto.classList.add("assunto-text");
            assunto.textContent = `Assunto: ${file.assunto}`;
            listItem.appendChild(assunto);

            const downloadButton = document.createElement('button');
            downloadButton.textContent = 'Baixar';
            downloadButton.classList.add("download-button");

            downloadButton.addEventListener('click', () => {
                const link = document.createElement('a');
                link.href = file.url;
                link.download = file.nome_arquivo;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });

            listItem.appendChild(downloadButton);

            fileList.appendChild(listItem);
        });
    } catch (error) {
        console.error("Erro ao buscar arquivos:", error);
    }
}

window.addEventListener('DOMContentLoaded', fetchFiles);
