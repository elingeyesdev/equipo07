// public/js/agro-wizard.js
document.addEventListener('DOMContentLoaded', function() {
    const wizard = document.querySelector('[data-agro-wizard]');
    if (!wizard) return;

    const form = wizard.closest('form');
    const steps = Array.from(wizard.querySelectorAll('[data-wizard-step]'));
    const indicators = Array.from(wizard.querySelectorAll('[data-wizard-go-to]'));
    const prevButton = wizard.querySelector('[data-wizard-prev]');
    const nextButton = wizard.querySelector('[data-wizard-next]');
    const submitButton = wizard.querySelector('[data-wizard-submit]');
    const errorSummary = wizard.querySelector('[data-wizard-error-summary]');
    const progressBar = wizard.querySelector('[data-wizard-progressbar]');
    const currentLabel = wizard.querySelector('[data-wizard-current-label]');
    
    // Obtenemos los errores de Laravel si es que se imprimieron en un tag <script> antes que este
    const serverErrors = typeof window.laravelErrors !== 'undefined' ? window.laravelErrors : {};
    let currentStep = 0;

    form.setAttribute('novalidate', 'novalidate');

    function normalizeFieldName(name) {
        return name.replace(/\[\]$/, '').replace(/\.\d+$/, '');
    }

    function findControlByErrorKey(key) {
        const normalized = normalizeFieldName(key);
        return form.querySelector(`[name="${normalized}"]`) || form.querySelector(`[name="${normalized}[]"]`);
    }

    function getStepForField(key) {
        const control = findControlByErrorKey(key);
        if (!control) return 0;
        const stepSection = control.closest('[data-wizard-step]');
        return stepSection ? parseInt(stepSection.getAttribute('data-wizard-step')) : 0;
    }

    function fieldLabel(control) {
        const group = control.closest('.form-group');
        const label = group ? group.querySelector('label') : null;
        return label ? label.textContent.replace('*', '').trim() : 'este campo';
    }

    function validationMessage(control) {
        if (control.validity.valueMissing) return `Completa ${fieldLabel(control).toLowerCase()} para continuar.`;
        if (control.validity.rangeUnderflow) return `Ingresa un valor igual o mayor a ${control.min}.`;
        if (control.validity.rangeOverflow) return `Ingresa un valor igual o menor a ${control.max}.`;
        if (control.validity.typeMismatch) return 'Ingresa un valor con el formato correcto.';
        return control.validationMessage || 'Revisa este campo antes de continuar.';
    }

    function feedbackElement(control) {
        const holder = control.closest('.input-group') || control;
        let feedback = holder.nextElementSibling;
        if (!feedback || !feedback.classList.contains('wizard-field-error')) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback wizard-field-error';
            holder.insertAdjacentElement('afterend', feedback);
        }
        return feedback;
    }

    function setFieldError(control, message) {
        control.classList.add('is-invalid');
        feedbackElement(control).textContent = message;
    }

    function clearFieldError(control) {
        control.classList.remove('is-invalid');
        const group = control.closest('.input-group') || control;
        const feedback = group.nextElementSibling;
        if (feedback && feedback.classList.contains('wizard-field-error')) {
            feedback.textContent = '';
        }
    }

    function validateStep(index, shouldFocus = true) {
        const controls = Array.from(steps[index].querySelectorAll('input, select, textarea'))
            .filter(control => !control.disabled && control.type !== 'hidden');
        const invalidControls = [];

        controls.forEach(control => {
            clearFieldError(control);
            if (!control.checkValidity()) {
                invalidControls.push(control);
                setFieldError(control, validationMessage(control));
            }
        });

        steps[index].classList.toggle('has-errors', invalidControls.length > 0);
        indicators[index].classList.toggle('has-errors', invalidControls.length > 0);

        if (invalidControls.length > 0) {
            errorSummary.textContent = 'Revisa los campos marcados antes de continuar.';
            errorSummary.classList.remove('d-none');
            if (shouldFocus) {
                invalidControls[0].focus({ preventScroll: true });
                invalidControls[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        } else {
            errorSummary.classList.add('d-none');
        }
        return invalidControls.length === 0;
    }

    function validateUntil(targetStep) {
        for (let index = 0; index < targetStep; index++) {
            if (!validateStep(index, false)) {
                showStep(index);
                validateStep(index, true);
                return false;
            }
        }
        return true;
    }

    function refreshMap() {
        if (typeof map !== 'undefined') {
            setTimeout(function() { map.invalidateSize(); }, 180);
        }
    }

    function showStep(index) {
        const previousStep = currentStep;
        currentStep = Math.max(0, Math.min(index, steps.length - 1));
        const direction = currentStep >= previousStep ? 'forward' : 'backward';

        steps.forEach((step, stepIndex) => {
            const isActive = stepIndex === currentStep;
            step.classList.toggle('is-active', isActive);
            step.classList.toggle('is-forward', isActive && direction === 'forward');
            step.classList.toggle('is-backward', isActive && direction === 'backward');
            step.style.display = isActive ? 'block' : 'none';
        });

        indicators.forEach((indicator, indicatorIndex) => {
            indicator.classList.toggle('is-active', indicatorIndex === currentStep);
            indicator.classList.toggle('is-complete', indicatorIndex < currentStep);
            indicator.setAttribute('aria-current', indicatorIndex === currentStep ? 'step' : 'false');

            const status = indicator.querySelector('[data-wizard-step-status]');
            if (status) {
                if (indicatorIndex < currentStep) status.textContent = 'Completado';
                else if (indicatorIndex === currentStep) status.textContent = 'En progreso';
                else status.textContent = 'Pendiente';
            }
        });

        prevButton.disabled = currentStep === 0;
        nextButton.classList.toggle('d-none', currentStep === steps.length - 1);
        submitButton.classList.toggle('d-none', currentStep !== steps.length - 1);
        errorSummary.classList.add('d-none');

        if (progressBar) progressBar.style.width = `${((currentStep + 1) / steps.length) * 100}%`;
        if (currentLabel) currentLabel.textContent = `Paso ${currentStep + 1} de ${steps.length}`;
        if (steps[currentStep].querySelector('#map')) refreshMap();

        wizard.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    indicators.forEach(indicator => {
        indicator.addEventListener('click', function() {
            const targetStep = Number(this.getAttribute('data-wizard-go-to'));
            if (targetStep <= currentStep || validateUntil(targetStep)) {
                showStep(targetStep);
            }
        });
    });

    prevButton.addEventListener('click', () => showStep(currentStep - 1));
    nextButton.addEventListener('click', () => { if (validateStep(currentStep)) showStep(currentStep + 1); });

    form.addEventListener('submit', function(event) {
        for (let index = 0; index < steps.length; index++) {
            if (!validateStep(index, false)) {
                event.preventDefault();
                showStep(index);
                validateStep(index, true);
                return;
            }
        }
    });

    Object.entries(serverErrors).forEach(([key, messages]) => {
        const control = findControlByErrorKey(key);
        if (!control) return;
        setFieldError(control, messages[0]);
        const stepIndex = getStepForField(key);
        steps[stepIndex].classList.add('has-errors');
        indicators[stepIndex].classList.add('has-errors');
    });

    const firstServerError = Object.keys(serverErrors)[0];
    if (firstServerError) {
        showStep(getStepForField(firstServerError));
        errorSummary.textContent = 'Hay datos por corregir antes de guardar.';
        errorSummary.classList.remove('d-none');
    } else {
        showStep(0);
    }

    // LÓGICA DE IMÁGENES (DRAG & DROP)
    const input = document.getElementById('imagenes-input');
    const uploadZone = wizard.querySelector('[data-upload-zone]');
    const previewContainer = document.getElementById('preview-container');
    const countDisplay = document.getElementById('imagenes-count');
    
    // Obtener la cantidad de imágenes actuales del dataset en el HTML
    const currentImagesContainer = document.getElementById('imagenes-actuales');
    const imagenesActuales = currentImagesContainer ? parseInt(currentImagesContainer.dataset.count || '0') : 0;
    
    let imagenesNuevas = 0;
    let imagenesAEliminar = [];
    let fileMap = new Map();

    document.querySelectorAll('.eliminar-imagen').forEach(btn => {
        btn.addEventListener('click', function() {
            const imagenId = this.getAttribute('data-imagen-id');
            const imagenItem = this.closest('.imagen-item');
            const inputEliminar = imagenItem.querySelector('.imagen-eliminar-input');

            if (inputEliminar.value === '') {
                inputEliminar.value = imagenId;
                imagenItem.style.opacity = '0.5';
                this.innerHTML = '<i class="fas fa-undo"></i>';
                imagenesAEliminar.push(imagenId);
            } else {
                inputEliminar.value = '';
                imagenItem.style.opacity = '1';
                this.innerHTML = '<i class="fas fa-times"></i>';
                imagenesAEliminar = imagenesAEliminar.filter(id => id !== imagenId);
            }
            updateCount();
        });
    });

    function updateCount() {
        if(!countDisplay || !uploadZone) return;
        const total = imagenesActuales - imagenesAEliminar.length + imagenesNuevas;
        countDisplay.textContent = `Total de imágenes: ${total} / 3`;
        uploadZone.classList.toggle('has-files', total > 0);

        if (total > 3) {
            countDisplay.className = 'text-danger mt-2 fw-bold';
            countDisplay.textContent += ' (Excede el límite de 3 imágenes)';
            if(input) input.setCustomValidity('Puedes publicar máximo 3 imágenes.');
        } else {
            countDisplay.className = 'text-muted mt-2';
            if(input) input.setCustomValidity('');
        }
    }

    if (uploadZone && input) {
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadZone.addEventListener(eventName, e => {
                e.preventDefault(); uploadZone.classList.add('is-dragover');
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadZone.addEventListener(eventName, e => {
                e.preventDefault(); uploadZone.classList.remove('is-dragover');
            });
        });

        uploadZone.addEventListener('drop', e => {
            const files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'));
            const dataTransfer = new DataTransfer();
            files.forEach(f => dataTransfer.items.add(f));
            input.files = dataTransfer.files;
            input.dispatchEvent(new Event('change', { bubbles: true }));
        });

        input.addEventListener('change', function(e) {
            previewContainer.innerHTML = '';
            imagenesNuevas = 0; fileMap.clear();
            const files = Array.from(e.target.files);
            const maxFiles = Math.max(0, 3 - (imagenesActuales - imagenesAEliminar.length));
            const dataTransfer = new DataTransfer();

            files.slice(0, maxFiles).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const fileId = Date.now() + '-' + index;
                    fileMap.set(fileId, file);
                    dataTransfer.items.add(file);

                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const col = document.createElement('div');
                        col.className = 'col-md-3 mb-3';
                        col.setAttribute('data-file-id', fileId);
                        col.innerHTML = `
                            <div class="position-relative">
                                <img src="${event.target.result}" class="img-thumbnail" style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">
                                <button type="button" class="btn btn-sm btn-danger position-absolute eliminar-preview" data-file-id="${fileId}">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `;
                        previewContainer.appendChild(col);
                        imagenesNuevas++; updateCount();

                        col.querySelector('.eliminar-preview').addEventListener('click', function() {
                            const fileIdToRemove = this.getAttribute('data-file-id');
                            fileMap.delete(fileIdToRemove);
                            const updatedTransfer = new DataTransfer();
                            fileMap.forEach(f => updatedTransfer.items.add(f));
                            input.files = updatedTransfer.files;
                            col.remove();
                            imagenesNuevas--; updateCount();
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });
            input.files = dataTransfer.files;
            updateCount();
        });
    }
});