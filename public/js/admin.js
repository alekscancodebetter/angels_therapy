(() => {
    const sidebar = document.querySelector('[data-admin-sidebar]');
    const menu = document.querySelector('[data-admin-menu]');
    const overlay = document.querySelector('[data-admin-overlay]');

    const close = () => {
        sidebar?.classList.remove('is-open');
        overlay?.classList.remove('is-open');
        menu?.setAttribute('aria-expanded', 'false');
    };

    menu?.addEventListener('click', () => {
        const open = menu.getAttribute('aria-expanded') !== 'true';
        sidebar?.classList.toggle('is-open', open);
        overlay?.classList.toggle('is-open', open);
        menu.setAttribute('aria-expanded', String(open));
    });

    overlay?.addEventListener('click', close);
    window.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') close();
    });

    const requiredFieldMessages = {
        customer_id: 'Please choose a customer.',
        starts_at: 'Please choose a date and time.',
        duration_minutes: 'Please choose a duration.',
        status: 'Please choose a status.',
        name: 'Please enter a name.',
        email: 'Please enter an email address.',
        password: 'Please enter your password.',
        password_confirmation: 'Please confirm your password.',
    };

    document.querySelectorAll('[required]').forEach((field) => {
        field.addEventListener('invalid', () => {
            field.setCustomValidity('');

            if (field.validity.valueMissing) {
                field.setCustomValidity(requiredFieldMessages[field.name] || 'Please complete this field.');
            } else if (field.validity.typeMismatch && field.type === 'email') {
                field.setCustomValidity('Please enter a valid email address.');
            } else if (field.validity.badInput) {
                field.setCustomValidity('Please enter a valid value.');
            }
        });

        const clearValidationMessage = () => field.setCustomValidity('');
        field.addEventListener('input', clearValidationMessage);
        field.addEventListener('change', clearValidationMessage);
    });
})();
