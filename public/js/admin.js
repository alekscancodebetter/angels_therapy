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
})();
