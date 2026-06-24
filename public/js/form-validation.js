(function () {
    'use strict';

    var numericLimits = {
        precio: { min: 0, max: 10000000 },
        precio_dia: { min: 0, max: 10000000 },
        stock: { min: 0, max: 1000000 },
        cantidad: { min: 1, max: 1000000 },
        edad_valor: { min: 0, max: 100 },
        peso_actual: { min: 0, max: 100000 },
        horas_por_dia: { min: 1, max: 24 }
    };

    function fieldLabel(field) {
        var label = field.id ? document.querySelector('label[for="' + CSS.escape(field.id) + '"]') : null;
        var text = label ? label.textContent : (field.getAttribute('placeholder') || field.name || 'Este campo');
        return text.replace(/\s*\*\s*$/, '').trim();
    }

    function phoneDigits(value) {
        var digits = value.replace(/\D/g, '');
        return digits.indexOf('591') === 0 && digits.length > 8 ? digits.slice(3) : digits;
    }

    function customError(field) {
        var value = typeof field.value === 'string' ? field.value.trim() : field.value;
        if (field.required && !value) return fieldLabel(field) + ' es obligatorio; no puede quedar vacío.';

        if ((field.type === 'tel' || /telefono|celular/i.test(field.name)) && value) {
            var digits = phoneDigits(value);
            if (!/^[0-9+()\s-]+$/.test(value)) return 'El teléfono solo puede contener números, espacios, +, guiones o paréntesis.';
            if (digits.length < 7) return 'Número incompleto: faltan ' + (7 - digits.length) + ' dígito(s).';
            if (digits.length > 8) return 'El teléfono tiene demasiados dígitos; ingresa 7 u 8 dígitos (sin contar +591).';
        }

        if (field.type === 'number' && value !== '') {
            var number = Number(value);
            var min = field.min !== '' ? Number(field.min) : null;
            var max = field.max !== '' ? Number(field.max) : null;
            if (!Number.isFinite(number)) return 'Ingresa un número válido.';
            if (min !== null && number < min) return min === 0 ? 'No se permiten valores negativos.' : 'El valor mínimo permitido es ' + min + '.';
            if (max !== null && number > max) return 'El valor máximo permitido es ' + max.toLocaleString('es-BO') + '.';
        }

        if (field.validity.typeMismatch) return field.type === 'email' ? 'Ingresa un correo válido, por ejemplo nombre@dominio.com.' : 'El formato ingresado no es válido.';
        if (field.validity.tooShort) return 'Dato incompleto: faltan ' + (field.minLength - value.length) + ' carácter(es).';
        if (field.validity.tooLong) return 'El dato supera el máximo de ' + field.maxLength + ' caracteres.';
        if (field.validity.patternMismatch) return field.title || 'El dato no tiene el formato requerido.';
        if (field.validity.badInput) return 'Ingresa un número válido.';
        return '';
    }

    function feedbackFor(field) {
        var next = field.nextElementSibling;
        if (next && next.classList.contains('agro-validation-feedback')) return next;
        var feedback = document.createElement('small');
        feedback.className = 'agro-validation-feedback';
        feedback.setAttribute('aria-live', 'polite');
        field.insertAdjacentElement('afterend', feedback);
        return feedback;
    }

    function validate(field, force) {
        if (!field.willValidate || field.disabled || field.type === 'hidden') return true;
        field.setCustomValidity('');
        var message = customError(field);
        if (!message && !field.validity.valid) message = field.validationMessage;
        field.setCustomValidity(message);

        var show = force || field.dataset.agroTouched === 'true';
        var feedback = feedbackFor(field);
        feedback.textContent = show ? message : '';
        field.classList.toggle('agro-field-invalid', show && !!message);
        field.classList.toggle('agro-field-valid', show && !message && !!field.value);
        field.setAttribute('aria-invalid', message ? 'true' : 'false');
        return !message;
    }

    function configure(field) {
        var simpleName = (field.name || '').replace(/\[.*$/, '');
        if (numericLimits[simpleName] && field.type === 'number') {
            if (!field.hasAttribute('min')) field.min = numericLimits[simpleName].min;
            if (!field.hasAttribute('max')) field.max = numericLimits[simpleName].max;
        }
        if ((field.type === 'tel' || /telefono|celular/i.test(field.name)) && field.type !== 'hidden') {
            field.setAttribute('inputmode', 'tel');
            field.setAttribute('maxlength', '18');
            field.setAttribute('autocomplete', 'tel');
        }
        if (field.required && field.id) {
            var label = document.querySelector('label[for="' + CSS.escape(field.id) + '"]');
            if (label && !label.querySelector('.agro-required-mark') && !/\*\s*$/.test(label.textContent)) {
                label.insertAdjacentHTML('beforeend', '<span class="agro-required-mark" aria-hidden="true">*</span>');
            }
        }
        field.addEventListener('input', function () { validate(field, false); });
        field.addEventListener('change', function () { validate(field, false); });
        field.addEventListener('blur', function () {
            field.dataset.agroTouched = 'true';
            validate(field, true);
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('form').forEach(function (form) {
            if ((form.method || '').toLowerCase() === 'get' || form.hasAttribute('novalidate')) return;
            var fields = Array.from(form.querySelectorAll('input, select, textarea'));
            fields.forEach(configure);
            form.addEventListener('submit', function (event) {
                var firstInvalid = null;
                fields.forEach(function (field) {
                    field.dataset.agroTouched = 'true';
                    if (!validate(field, true) && !firstInvalid) firstInvalid = field;
                });
                if (firstInvalid) {
                    event.preventDefault();
                    event.stopImmediatePropagation();
                    firstInvalid.focus({ preventScroll: true });
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }, true);
        });
    });
})();
