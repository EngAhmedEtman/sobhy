import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import flatpickr from 'flatpickr';
import { Arabic } from 'flatpickr/dist/l10n/ar.js';

window.Alpine = Alpine;
flatpickr.l10ns.ar = Arabic;
window.flatpickr = flatpickr;

const improveAccessibility = (root = document) => {
    root.querySelectorAll('button:not([aria-label])').forEach((button) => {
        if (button.textContent.trim()) return;

        const action = button.getAttribute('@click') || button.getAttribute('x-on:click') || '';
        const label = button.getAttribute('title') || (action.includes('false') ? 'إغلاق النافذة' : 'تنفيذ الإجراء');
        button.setAttribute('aria-label', label);
    });

    root.querySelectorAll('input:not([aria-label]), select:not([aria-label]), textarea:not([aria-label])').forEach((control) => {
        const hasAssociatedLabel = control.id && document.querySelector(`label[for=${CSS.escape(control.id)}]`);
        if (hasAssociatedLabel || control.closest('label') || control.type === 'hidden') return;

        const fallbackLabel = control.getAttribute('placeholder') || control.getAttribute('name');
        if (fallbackLabel) control.setAttribute('aria-label', fallbackLabel);
    });

    root.querySelectorAll('button svg, a svg').forEach((icon) => {
        icon.setAttribute('aria-hidden', 'true');
        icon.setAttribute('focusable', 'false');
    });
};

document.addEventListener('DOMContentLoaded', () => improveAccessibility());
document.addEventListener('htmx:afterSwap', (event) => improveAccessibility(event.target));

Alpine.plugin(collapse);
Alpine.start();
