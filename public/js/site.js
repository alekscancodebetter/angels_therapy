(() => {
    const header = document.querySelector('[data-header]');
    const menuToggle = document.querySelector('[data-menu-toggle]');
    const nav = document.querySelector('#main-nav');

    const updateHeader = () => header?.classList.toggle('is-scrolled', window.scrollY > 24);
    updateHeader();
    window.addEventListener('scroll', updateHeader, { passive: true });

    const closeMenu = () => {
        if (!menuToggle || !nav) return;
        menuToggle.setAttribute('aria-expanded', 'false');
        nav.classList.remove('is-open');
        document.body.classList.remove('menu-open');
    };

    menuToggle?.addEventListener('click', () => {
        const open = menuToggle.getAttribute('aria-expanded') === 'true';
        menuToggle.setAttribute('aria-expanded', String(!open));
        nav?.classList.toggle('is-open', !open);
        document.body.classList.toggle('menu-open', !open);
    });

    nav?.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMenu));

    const revealItems = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px' });
        revealItems.forEach((item) => observer.observe(item));
    } else {
        revealItems.forEach((item) => item.classList.add('is-visible'));
    }

    const stories = [...document.querySelectorAll('[data-story-slider] .story')];
    const current = document.querySelector('[data-story-current]');
    let activeStory = 0;

    const showStory = (index) => {
        if (!stories.length) return;
        activeStory = (index + stories.length) % stories.length;
        stories.forEach((story, storyIndex) => {
            const active = storyIndex === activeStory;
            story.hidden = !active;
            story.classList.toggle('is-active', active);
        });
        if (current) current.textContent = String(activeStory + 1);
    };

    document.querySelector('[data-story-prev]')?.addEventListener('click', () => showStory(activeStory - 1));
    document.querySelector('[data-story-next]')?.addEventListener('click', () => showStory(activeStory + 1));

    document.querySelectorAll('[data-video]').forEach((button) => {
        button.addEventListener('click', () => {
            if (button.querySelector('iframe')) return;
            const videoId = button.dataset.video;
            const iframe = document.createElement('iframe');
            iframe.src = `https://www.youtube-nocookie.com/embed/${videoId}?autoplay=1&rel=0`;
            iframe.title = button.getAttribute('aria-label') || 'Video review';
            iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
            iframe.allowFullscreen = true;
            button.append(iframe);
        });
    });
})();
