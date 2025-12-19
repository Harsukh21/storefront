(function () {
    const container = document.querySelector('[data-toast-container]');
    const template = document.querySelector('#toast-template');
    if (!container || !template) {
        return;
    }

    const AUTO_DISMISS = 4500;
    const typeMap = {
        success: 'success',
        warning: 'warning',
        danger: 'danger',
        error: 'danger',
        info: 'info',
    };

    const removeToast = (toast) => {
        if (!toast) {
            return;
        }
        toast.classList.add('toast-leave');
        setTimeout(() => toast.remove(), 250);
    };

    const showToast = ({ type = 'info', message }) => {
        if (!message) {
            return;
        }

        const normalized = typeMap[type] ?? 'info';
        const clone = template.content.cloneNode(true);
        const toast = clone.querySelector('[data-toast]');
        const messageNode = clone.querySelector('[data-toast-message]');
        const dismissBtn = clone.querySelector('[data-toast-dismiss]');

        toast.dataset.toastType = normalized;
        messageNode.textContent = message;
        container.appendChild(clone);

        requestAnimationFrame(() => {
            toast.classList.add('toast-enter');
        });

        const timeout = setTimeout(() => removeToast(toast), AUTO_DISMISS);

        dismissBtn.addEventListener('click', () => {
            clearTimeout(timeout);
            removeToast(toast);
        });
    };

    window.Toast = {
        show: showToast,
        success: (message) => showToast({ type: 'success', message }),
        warning: (message) => showToast({ type: 'warning', message }),
        danger: (message) => showToast({ type: 'danger', message }),
        error: (message) => showToast({ type: 'danger', message }),
        info: (message) => showToast({ type: 'info', message }),
    };

    document.querySelectorAll('[data-flash-toast]').forEach((node) => {
        showToast({
            type: node.dataset.toastType || 'info',
            message: node.dataset.toastMessage || '',
        });
        node.remove();
    });
})();

