@php
    $draftKey = $draftKey ?? md5(request()->path());
@endphp

<script>
    (function() {
        const script = document.currentScript;
        const form = script ? script.closest('form') : null;

        if (!form || !window.localStorage) {
            return;
        }

        const storageKey = 'agrovida.form-draft.{{ $draftKey }}';
        const saveButton = form.querySelector('[data-draft-save]');
        const discardButton = form.querySelector('[data-draft-discard]');
        const status = form.querySelector('[data-draft-status]');
        const skipTypes = ['file', 'button', 'submit', 'reset'];
        let saveTimer = null;
        let restoring = false;

        function draftFields() {
            return Array.from(form.elements).filter(function(field) {
                return field.name && !field.disabled && !skipTypes.includes(field.type);
            });
        }

        function showStatus(message) {
            if (!status) return;

            status.textContent = message;
            status.classList.remove('d-none');
        }

        function readDraft() {
            try {
                return JSON.parse(localStorage.getItem(storageKey) || '{}');
            } catch (error) {
                return {};
            }
        }

        function collectDraft() {
            const draft = {};

            draftFields().forEach(function(field) {
                if (field.type === 'checkbox') {
                    draft[field.name] = field.checked;
                    return;
                }

                if (field.type === 'radio') {
                    if (field.checked) {
                        draft[field.name] = field.value;
                    }
                    return;
                }

                draft[field.name] = field.value;
            });

            return draft;
        }

        function saveDraft(manual) {
            if (restoring) return;

            localStorage.setItem(storageKey, JSON.stringify(collectDraft()));

            if (manual) {
                showStatus('Borrador guardado en este navegador.');
            }
        }

        function scheduleSave() {
            clearTimeout(saveTimer);
            saveTimer = setTimeout(function() {
                saveDraft(false);
            }, 500);
        }

        function restoreDraft() {
            const draft = readDraft();
            const names = Object.keys(draft);

            if (!names.length) {
                return;
            }

            restoring = true;

            draftFields().forEach(function(field) {
                if (!Object.prototype.hasOwnProperty.call(draft, field.name)) {
                    return;
                }

                const value = draft[field.name];

                if (field.type === 'checkbox') {
                    field.checked = Boolean(value);
                } else if (field.type === 'radio') {
                    field.checked = field.value === value;
                } else {
                    field.value = value;
                }

                field.dispatchEvent(new Event('input', { bubbles: true }));
                field.dispatchEvent(new Event('change', { bubbles: true }));
            });

            restoring = false;
            showStatus('Borrador restaurado. Los archivos deben seleccionarse nuevamente.');
            form.dispatchEvent(new CustomEvent('form-draft:restored', {
                bubbles: true,
                detail: {
                    draft: draft,
                },
            }));
        }

        form.addEventListener('input', function(event) {
            if (event.target && event.target.name) {
                scheduleSave();
            }
        });

        form.addEventListener('change', function(event) {
            if (event.target && event.target.name) {
                scheduleSave();
            }
        });

        if (saveButton) {
            saveButton.addEventListener('click', function() {
                saveDraft(true);
            });
        }

        if (discardButton) {
            discardButton.addEventListener('click', function() {
                localStorage.removeItem(storageKey);
                showStatus('Borrador descartado.');
            });
        }

        form.addEventListener('submit', function() {
            localStorage.removeItem(storageKey);
        });

        restoreDraft();
    })();
</script>
