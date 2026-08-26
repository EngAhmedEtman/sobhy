import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import flatpickr from 'flatpickr';
import { Arabic } from 'flatpickr/dist/l10n/ar.js';

window.Alpine = Alpine;
flatpickr.l10ns.ar = Arabic;
window.flatpickr = flatpickr;

window.invoiceProductDropdown = (minimumWidth = 220) => ({
    open: false,
    search: '',
    dropdownStyle: {},

    toggle() {
        if (this.open) {
            this.close();
            return;
        }

        this.position();
        this.open = true;
        this.$nextTick(() => this.position());
    },

    close() {
        this.open = false;
        this.search = '';
    },

    position() {
        const trigger = this.$refs.trigger;
        if (!trigger) return;

        const rect = trigger.getBoundingClientRect();
        const viewportPadding = 8;
        const estimatedMenuHeight = 240;
        const availableBelow = window.innerHeight - rect.bottom;
        const showAbove = availableBelow < estimatedMenuHeight && rect.top > availableBelow;
        const width = Math.min(
            Math.max(rect.width, minimumWidth),
            window.innerWidth - (viewportPadding * 2),
        );
        const left = Math.min(
            Math.max(viewportPadding, rect.right - width),
            window.innerWidth - width - viewportPadding,
        );

        this.dropdownStyle = {
            position: 'fixed',
            left: `${left}px`,
            top: showAbove ? 'auto' : `${rect.bottom + 4}px`,
            bottom: showAbove ? `${window.innerHeight - rect.top + 4}px` : 'auto',
            width: `${width}px`,
            zIndex: 1000,
        };
    },
});

Alpine.store('toast', {
    visible: false,
    message: '',
    type: 'success',
    progress: 100,
    progressTimer: null,
    hideTimer: null,

    display(message, type = 'success') {
        window.clearTimeout(this.progressTimer);
        window.clearTimeout(this.hideTimer);

        this.message = message;
        this.type = type;
        this.progress = 100;
        this.visible = true;

        this.progressTimer = window.setTimeout(() => {
            this.progress = 0;
        }, 100);

        this.hideTimer = window.setTimeout(() => {
            this.visible = false;
        }, 4500);
    },

    hide() {
        window.clearTimeout(this.progressTimer);
        window.clearTimeout(this.hideTimer);
        this.visible = false;
    },
});

window.showToast = (message, type = 'success') => {
    Alpine.store('toast').display(message, type);
};

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
