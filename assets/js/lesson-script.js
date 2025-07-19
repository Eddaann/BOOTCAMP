document.addEventListener('DOMContentLoaded', () => {
    let currentStep = 1;
    const totalSteps = 10;
    const wizard = document.querySelector('.activity-wizard');
    if (!wizard) return;

    const htmlBase = document.querySelector('.html-base')?.innerHTML || '';

    function updateProgress() {
        const progressBar = document.querySelector(`.activity-step.active .progress`);
        if (progressBar) {
            let progressPercentage = ((currentStep - 1) / totalSteps) * 100;
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
            if (prevCodeEl && nextCodeEl) {
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

        if (document.getElementById('dynamic-styles')) { // Si es una lección de CSS
            const dynamicStyles = document.getElementById('dynamic-styles');
            dynamicStyles.innerHTML = codeEl.value;
            previewEl.innerHTML = htmlBase;
        } else { // Si es una lección de HTML
            previewEl.innerHTML = codeEl.value;
        }
    }
    
    function updateAllPreviews() {
        for (let i = 1; i <= totalSteps + 2; i++) { // +2 para incluir desafío y final
            updatePreview(i);
        }
    }

    // --- Funciones de Verificación --- //
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
        if (code.includes(`<${tag}>`)) {
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
        if (link && link.innerText.trim().toLowerCase() === text.toLowerCase() && link.href.trim().toLowerCase() === href.toLowerCase()) {
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
        if (code.includes(commentText)) {
            showFeedback(step, '¡Comentario añadido! Así se hace.', true);
        } else {
            showFeedback(step, 'No encuentro el comentario. Recuerda usar .', false);
        }
    };
    
    window.checkCss = (step, selector, property, value) => {
        const code = document.getElementById(`code-${step}`).value;
        const styleEl = document.createElement('style');
        styleEl.innerHTML = code;
        document.head.appendChild(styleEl);
        const previewBox = document.getElementById(`preview-${step}`);
        const elementToTest = previewBox.querySelector(selector);
        let isCorrect = false;
        if (elementToTest) {
            const computed = window.getComputedStyle(elementToTest);
            const computedValue = computed[property];
            if (computedValue) {
                // Normaliza los valores de color de RGB a nombres de color si es posible
                let normalizedComputed = computedValue.toLowerCase();
                let normalizedValue = value.toLowerCase();
                if (normalizedComputed.startsWith('rgb')) {
                    const colorMap = { 'purple': 'rgb(128, 0, 128)', 'lightblue': 'rgb(173, 216, 230)', 'salmon': 'rgb(250, 128, 114)' };
                    if (colorMap[normalizedValue] === normalizedComputed) {
                        isCorrect = true;
                    }
                } else if (normalizedComputed.includes(normalizedValue)) {
                    isCorrect = true;
                }
            }
        }
        if (isCorrect) {
            showFeedback(step, '¡Estilo aplicado correctamente! Puedes continuar.', true);
        } else {
            showFeedback(step, `No se detectó el estilo. Revisa el selector "${selector}" y la propiedad "${property}: ${value};".`, false);
        }
        document.head.removeChild(styleEl);
    };

    window.checkCssComment = (step, commentText) => {
        const code = document.getElementById(`code-${step}`).value;
        if (code.includes(commentText)) {
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
              showFeedback(step, 'No encuentro la media query. Recuerda empezar con @media (max-width: 600px) { ... }', false);
              return;
         }
         if (selector && !code.includes(selector)){
              showFeedback(step, `Dentro de la media query, no encuentro el selector '${selector}'.`, false);
              return;
         }
         showFeedback(step, '¡Estructura de Media Query correcta! Puedes continuar.', true);
    }

    // Inicialización
    document.querySelectorAll('.sandbox-editor textarea').forEach(textarea => {
        textarea.addEventListener('keyup', () => {
            const step = textarea.id.split('-')[1];
            updatePreview(step);
        });
    });
    
    updateAllPreviews();
    updateProgress();
});