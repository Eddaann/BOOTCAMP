// Este archivo no necesita cambios, funciona bien con la estructura actual.
// Se incluye para que tengas el proyecto completo.
document.addEventListener('DOMContentLoaded', () => {
    let currentStep = 1;
    const wizard = document.querySelector('.activity-wizard');
    if (!wizard) return;

    // Ajuste para encontrar el número total de pasos dinámicamente
    const totalSteps = wizard.querySelectorAll('.activity-step[data-step]').length - 2; // No contar los pasos de desafío y final

    const htmlBase = document.querySelector('.html-base')?.innerHTML || '';

    function updateProgress() {
        const activeStepEl = document.querySelector('.activity-step.active');
        if (!activeStepEl) return;

        const currentStepNumber = parseInt(activeStepEl.dataset.step, 10);
        const progressBar = activeStepEl.querySelector('.progress');
        
        if (progressBar && currentStepNumber <= totalSteps) {
            let progressPercentage = ((currentStepNumber - 1) / totalSteps) * 100;
            progressBar.style.width = `${progressPercentage}%`;
        }
    }

    function showFeedback(step, message, isSuccess) {
        const feedbackEl = document.getElementById(`feedback-${step}`);
        if (!feedbackEl) return;
        feedbackEl.textContent = message;
        feedbackEl.className = `feedback-message ${isSuccess ? 'success' : 'error'}`;
        if (isSuccess) {
            const nextButton = document.querySelector(`[data-step="${step}"] .btn-next`);
            if (nextButton) nextButton.style.display = 'inline-block';
            const verifyButton = document.querySelector(`[data-step="${step}"] .btn-verify`);
            if(verifyButton) verifyButton.style.display = 'none';
        }
    }

    window.nextStep = function(step) {
        const currentEl = document.querySelector(`[data-step="${step}"]`);
        const nextEl = document.querySelector(`[data-step="${step + 1}"]`);
        
        if (currentEl && nextEl) {
            currentEl.classList.remove('active');
            nextEl.classList.add('active');
            currentStep = step + 1;
            
            const prevCodeEl = document.getElementById(`code-${step}`);
            const nextCodeEl = document.getElementById(`code-${step + 1}`);
            // Solo copiar el código si es relevante (HTML a HTML, CSS a CSS)
            if (prevCodeEl && nextCodeEl && prevCodeEl.id.startsWith('code-') && nextCodeEl.id.startsWith('code-')) {
                nextCodeEl.value = prevCodeEl.value;
            }
            
            updateAllPreviews();
            updateProgress();
        }
    }

    function updatePreview(step) {
        const codeEl = document.getElementById(`code-${step}`);
        const previewEl = document.getElementById(`preview-${step}`);
        if (!codeEl || !previewEl) return;

        // Mejorado para manejar lecciones de CSS y HTML
        const isCssLesson = !!document.getElementById('dynamic-styles');
        
        if (isCssLesson) {
            const dynamicStyles = document.getElementById('dynamic-styles');
            dynamicStyles.innerHTML = codeEl.value;
            previewEl.innerHTML = htmlBase;
        } else {
            previewEl.innerHTML = codeEl.value;
        }
    }
    
    function updateAllPreviews() {
        const allSteps = document.querySelectorAll('.activity-step[data-step]');
        allSteps.forEach(stepEl => {
            const stepNum = stepEl.dataset.step;
            updatePreview(stepNum);
        });
    }

    // --- Funciones de Verificación (sin cambios) --- //
    window.checkActivity = (step, tag, text) => {
        const code = document.getElementById(`code-${step}`).value;
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = code;
        const element = tempDiv.querySelector(tag);
        if (element && element.innerText.trim().toLowerCase() === text.toLowerCase()) {
            showFeedback(step, '¡Correcto! Puedes avanzar.', true);
        } else {
            showFeedback(step, `Casi... Revisa que la etiqueta <${tag}> y el texto "${text}" sean correctos.`, false);
        }
    };
    
    window.checkSimpleTag = (step, tag) => {
        const code = document.getElementById(`code-${step}`).value;
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = code;
        if (tempDiv.querySelector(tag) || code.includes(`<${tag}>`)) {
            showFeedback(step, '¡Bien hecho! Etiqueta encontrada.', true);
        } else {
            showFeedback(step, `No encuentro la etiqueta <${tag}>. ¡Inténtalo de nuevo!`, false);
        }
    };

    window.checkListActivity = (step, items) => {
        const code = document.getElementById(`code-${step}`).value;
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = code;
        const listItems = tempDiv.querySelectorAll('ul > li');
        let allFound = items.every(itemText => 
            Array.from(listItems).some(li => li.innerText.trim().toLowerCase() === itemText.toLowerCase())
        );
        if (listItems.length >= items.length && allFound) {
            showFeedback(step, '¡Lista perfecta! Continuemos.', true);
        } else {
            showFeedback(step, 'Parece que la lista no es correcta. Asegúrate de usar <ul> y <li> con los textos pedidos.', false);
        }
    };

    window.checkLinkActivity = (step, text, href) => {
        const code = document.getElementById(`code-${step}`).value;
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = code;
        const link = tempDiv.querySelector('a');
        if (link && link.innerText.trim().toLowerCase() === text.toLowerCase() && (link.href.trim() === href || link.href.trim() === href.slice(0,-1))) {
            showFeedback(step, '¡Enlace creado correctamente! Excelente.', true);
        } else {
            showFeedback(step, 'Revisa el enlace. El texto o el atributo href no coinciden.', false);
        }
    };
    
    window.checkImageActivity = (step, src, alt) => {
        const code = document.getElementById(`code-${step}`).value;
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = code;
        const img = tempDiv.querySelector('img');
        if (img && img.src.includes(src) && img.alt.trim().toLowerCase() === alt.toLowerCase()) {
            showFeedback(step, '¡Imagen insertada! Buen trabajo.', true);
        } else {
            showFeedback(step, 'La imagen no es correcta. Verifica los atributos src y alt.', false);
        }
    };

    window.checkTagInParagraph = (step, tag, text) => {
        const code = document.getElementById(`code-${step}`).value;
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = code;
        const p = tempDiv.querySelector('p');
        const targetTag = p ? p.querySelector(tag) : null;
        if (targetTag && (!text || targetTag.innerText.trim().toLowerCase().includes(text.toLowerCase()))) {
            showFeedback(step, `¡Etiqueta <${tag}> aplicada correctamente!`, true);
        } else {
            showFeedback(step, `No encontré la etiqueta <${tag}> aplicada al texto correcto dentro del párrafo.`, false);
        }
    };

    window.checkCommentActivity = (step, commentText) => {
        const code = document.getElementById(`code-${step}`).value;
        if (code.includes('<!--') && code.includes('-->')) {
            showFeedback(step, '¡Comentario añadido! Así se hace.', true);
        } else {
            showFeedback(step, 'No encuentro el comentario. Recuerda usar <!-- comentario -->.', false);
        }
    };
    
    window.checkCss = (step, selector, property, value) => {
        const code = document.getElementById(`code-${step}`).value;
        const previewEl = document.getElementById(`preview-${step}`);
        
        // Crear un iframe para aislar los estilos y evitar conflictos
        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        previewEl.appendChild(iframe);
        
        const iDoc = iframe.contentDocument;
        iDoc.open();
        iDoc.write(`
            <style>${code}</style>
            <div id="test-container">${htmlBase}</div>
        `);
        iDoc.close();

        const elementToTest = iDoc.querySelector(selector);
        let isCorrect = false;

        if (elementToTest) {
            const computed = window.getComputedStyle(elementToTest);
            const computedValue = computed[property];
            
            // Normalización simple para que la verificación sea más flexible
            const normalize = (str) => str.toLowerCase().replace(/\s/g, '');

            if (computedValue && normalize(computedValue).includes(normalize(value))) {
                isCorrect = true;
            }
        }
        
        previewEl.removeChild(iframe); // Limpiar el iframe

        if (isCorrect) {
            showFeedback(step, '¡Estilo aplicado correctamente! Puedes continuar.', true);
        } else {
            showFeedback(step, `No se detectó el estilo. Revisa el selector "${selector}" y la propiedad "${property}: ${value};".`, false);
        }
    };

    window.checkCssComment = (step, commentText) => {
        const code = document.getElementById(`code-${step}`).value;
        if (code.includes('/*') && code.includes('*/')) {
            showFeedback(step, '¡Comentario correcto! Vamos al siguiente.', true);
        } else {
            showFeedback(step, 'No encuentro el comentario en el formato correcto: /* comentario */.', false);
        }
    };

    window.checkFormInput = (step, type, id) => {
        const code = document.getElementById(`code-${step}`).value;
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = code;
        const input = tempDiv.querySelector(`input[type="${type}"][id="${id}"]`);
        const label = tempDiv.querySelector(`label[for="${id}"]`);
        if (input && label) {
            showFeedback(step, '¡Campo de formulario creado a la perfección!', true);
        } else {
            showFeedback(step, `Asegúrate de tener la etiqueta <label for="${id}"> y el <input type="${type}" id="${id}">.`, false);
        }
    };
    
    window.checkMediaRule = (step, selector, property, value) => {
         const code = document.getElementById(`code-${step}`).value;
         if (!code.includes('@media')) {
              showFeedback(step, 'No encuentro la media query. Recuerda empezar con @media (max-width: ...) { ... }', false);
              return;
         }
         if (selector && !code.includes(selector)){
              showFeedback(step, `Dentro de la media query, no encuentro el selector '${selector}'.`, false);
              return;
         }
         // Esta es una verificación básica, una completa requeriría un análisis más complejo.
         showFeedback(step, '¡Estructura de Media Query correcta! Puedes continuar.', true);
    }

    // Inicialización
    document.querySelectorAll('.sandbox-editor textarea').forEach(textarea => {
        textarea.addEventListener('input', () => { // Usar 'input' para una respuesta más inmediata
            const step = textarea.id.split('-')[1];
            updatePreview(step);
        });
    });
    
    updateAllPreviews();
    updateProgress();
});