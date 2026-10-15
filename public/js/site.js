(() => {
    const header = document.querySelector('[data-header]');
    const menuToggle = document.querySelector('[data-menu-toggle]');
    const nav = document.querySelector('#main-nav');
    const desktopHover = window.matchMedia('(hover: hover) and (pointer: fine)');
    const opensOnHover = () => desktopHover.matches && window.innerWidth > 820;

    const updateHeader = () => header?.classList.toggle('is-scrolled', window.scrollY > 24);
    updateHeader();
    window.addEventListener('scroll', updateHeader, { passive: true });

    const closeMenu = () => {
        if (!menuToggle || !nav) return;
        menuToggle.setAttribute('aria-expanded', 'false');
        nav.classList.remove('is-open');
        nav.querySelectorAll('.nav-dropdown[open]').forEach((dropdown) => dropdown.removeAttribute('open'));
        document.body.classList.remove('menu-open');
    };

    menuToggle?.addEventListener('click', () => {
        const open = menuToggle.getAttribute('aria-expanded') === 'true';
        menuToggle.setAttribute('aria-expanded', String(!open));
        nav?.classList.toggle('is-open', !open);
        document.body.classList.toggle('menu-open', !open);
    });

    nav?.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMenu));

    nav?.querySelectorAll('.nav-dropdown').forEach((dropdown) => {
        dropdown.addEventListener('mouseenter', () => {
            if (opensOnHover()) dropdown.setAttribute('open', '');
        });

        dropdown.addEventListener('mouseleave', () => {
            if (opensOnHover()) dropdown.removeAttribute('open');
        });

        dropdown.querySelector('summary')?.addEventListener('click', (event) => {
            if (opensOnHover() && event.detail > 0) event.preventDefault();
        });
    });

    document.addEventListener('click', (event) => {
        nav?.querySelectorAll('.nav-dropdown[open]').forEach((dropdown) => {
            if (!dropdown.contains(event.target)) dropdown.removeAttribute('open');
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') return;
        const openDropdown = nav?.querySelector('.nav-dropdown[open]');
        if (!openDropdown) return;
        openDropdown.removeAttribute('open');
        openDropdown.querySelector('summary')?.focus();
    });

    const bookingCalendar = document.querySelector('[data-booking-calendar]');

    if (bookingCalendar) {
        const availabilityUrl = bookingCalendar.dataset.availabilityUrl;
        const monthLabel = bookingCalendar.querySelector('[data-booking-month]');
        const previousButton = bookingCalendar.querySelector('[data-booking-previous]');
        const nextButton = bookingCalendar.querySelector('[data-booking-next]');
        const daysGrid = bookingCalendar.querySelector('[data-booking-days]');
        const loadingMessage = bookingCalendar.querySelector('[data-booking-loading]');
        const selectedDayLabel = bookingCalendar.querySelector('[data-booking-day]');
        const timeSlots = bookingCalendar.querySelector('[data-booking-times]');
        const selectedInput = bookingCalendar.querySelector('[data-booking-input]');
        const selectedAppointment = bookingCalendar.querySelector('[data-selected-appointment]');
        const submitButton = bookingCalendar.querySelector('[data-booking-submit]');
        let selectedSlot = bookingCalendar.dataset.selectedSlot || '';
        let selectedDate = selectedSlot.slice(0, 10);

        const setSelectedSlot = (day, slot) => {
            selectedSlot = slot.value;
            selectedDate = day.date;
            selectedInput.value = slot.value;
            selectedAppointment.textContent = `${day.label} at ${slot.label}`;
            submitButton.disabled = false;

            timeSlots.querySelectorAll('.booking-time').forEach((button) => {
                const selected = button.dataset.slot === selectedSlot;
                button.classList.toggle('is-selected', selected);
                button.setAttribute('aria-pressed', String(selected));
            });
        };

        const renderTimeSlots = (day) => {
            selectedDayLabel.textContent = day.label;
            timeSlots.replaceChildren();

            const selectedSlotOption = day.slots.find((slot) => slot.value === selectedSlot);
            if (selectedSlot && selectedDate === day.date && !selectedSlotOption?.available) {
                selectedSlot = '';
                selectedInput.value = '';
                selectedAppointment.textContent = 'Choose a time';
                submitButton.disabled = true;
            }

            if (!day.slots.length) {
                const emptyMessage = document.createElement('p');
                emptyMessage.textContent = 'No appointments remain on this day.';
                timeSlots.append(emptyMessage);
                return;
            }

            day.slots.forEach((slot) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'booking-time';
                button.dataset.slot = slot.value;
                button.disabled = !slot.available;
                button.textContent = slot.available ? slot.label : `${slot.label} · Booked`;
                button.setAttribute('aria-label', slot.available ? slot.label : `${slot.label}, booked`);
                button.setAttribute('aria-pressed', String(slot.available && slot.value === selectedSlot));

                if (slot.available && slot.value === selectedSlot) {
                    button.classList.add('is-selected');
                    selectedAppointment.textContent = `${day.label} at ${slot.label}`;
                    submitButton.disabled = false;
                }

                if (slot.available) {
                    button.addEventListener('click', () => setSelectedSlot(day, slot));
                }

                timeSlots.append(button);
            });
        };

        const selectDay = (day) => {
            if (selectedDate !== day.date) {
                selectedSlot = '';
                selectedInput.value = '';
                selectedAppointment.textContent = 'Choose a time';
                submitButton.disabled = true;
            }

            selectedDate = day.date;
            daysGrid.querySelectorAll('.booking-day').forEach((button) => {
                const selected = button.dataset.date === selectedDate;
                button.classList.toggle('is-selected', selected);
                button.setAttribute('aria-pressed', String(selected));
            });
            renderTimeSlots(day);
        };

        const renderCalendar = (calendar) => {
            monthLabel.textContent = calendar.label;
            previousButton.disabled = !calendar.previous_month;
            previousButton.dataset.month = calendar.previous_month || '';
            nextButton.disabled = !calendar.next_month;
            nextButton.dataset.month = calendar.next_month || '';
            daysGrid.replaceChildren();

            const firstDay = calendar.days[0];
            for (let index = 1; index < firstDay.weekday; index += 1) {
                const spacer = document.createElement('span');
                spacer.className = 'booking-day-spacer';
                spacer.setAttribute('aria-hidden', 'true');
                daysGrid.append(spacer);
            }

            calendar.days.forEach((day) => {
                const button = document.createElement('button');
                const availableCount = day.slots.filter((slot) => slot.available).length;
                button.type = 'button';
                button.className = 'booking-day';
                button.dataset.date = day.date;
                button.textContent = day.day;
                button.disabled = availableCount === 0;
                button.setAttribute('aria-label', availableCount
                    ? `${day.label}, ${availableCount} appointments available`
                    : `${day.label}, no appointments available`);
                button.setAttribute('aria-pressed', String(day.date === selectedDate));

                if (day.date === selectedDate && availableCount) {
                    button.classList.add('is-selected');
                }

                button.addEventListener('click', () => selectDay(day));
                daysGrid.append(button);
            });

            const selectedDay = calendar.days.find((day) => (
                day.date === selectedDate && day.slots.some((slot) => slot.available)
            ));
            if (selectedDay) {
                renderTimeSlots(selectedDay);
            } else if (selectedDate) {
                selectedDate = '';
                selectedSlot = '';
                selectedInput.value = '';
                selectedAppointment.textContent = 'Choose a date and time';
                submitButton.disabled = true;
            }
        };

        const loadCalendar = async (month) => {
            loadingMessage.hidden = false;
            loadingMessage.textContent = 'Checking available appointments…';
            previousButton.disabled = true;
            nextButton.disabled = true;

            try {
                const response = await fetch(`${availabilityUrl}?month=${encodeURIComponent(month)}`, {
                    headers: { Accept: 'application/json' },
                    credentials: 'same-origin',
                });

                if (!response.ok) throw new Error('Availability could not be loaded.');

                renderCalendar(await response.json());
                loadingMessage.hidden = true;
            } catch (error) {
                loadingMessage.textContent = 'The calendar could not be loaded. Please refresh the page or call Villy.';
            }
        };

        previousButton.addEventListener('click', () => {
            if (previousButton.dataset.month) loadCalendar(previousButton.dataset.month);
        });
        nextButton.addEventListener('click', () => {
            if (nextButton.dataset.month) loadCalendar(nextButton.dataset.month);
        });

        loadCalendar(bookingCalendar.dataset.initialMonth);
    }

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

    document.querySelectorAll('[data-google-reviews-carousel]').forEach((carousel) => {
        const track = carousel.querySelector('[data-google-reviews-track]');
        const cards = [...carousel.querySelectorAll('[data-google-review-card]')];
        const previousButton = carousel.querySelector('[data-google-reviews-prev]');
        const nextButton = carousel.querySelector('[data-google-reviews-next]');
        const current = carousel.querySelector('[data-google-reviews-current]');
        let activeIndex = 0;

        const visibleCount = () => window.innerWidth <= 820 ? 1 : 3;

        const updateCarousel = () => {
            if (!track || !cards.length) return;

            const visibleCards = visibleCount();
            const maximumStart = Math.max(0, cards.length - visibleCards);
            activeIndex = Math.min(activeIndex, maximumStart);

            const cardWidth = cards[0].getBoundingClientRect().width;
            const gap = Number.parseFloat(window.getComputedStyle(track).gap) || 0;
            track.style.transform = `translateX(-${activeIndex * (cardWidth + gap)}px)`;

            cards.forEach((card, index) => {
                const isVisible = index >= activeIndex && index < activeIndex + visibleCards;
                card.tabIndex = isVisible ? 0 : -1;
                card.setAttribute('aria-hidden', String(!isVisible));
            });

            if (current) {
                const lastVisible = Math.min(activeIndex + visibleCards, cards.length);
                current.textContent = visibleCards === 1
                    ? String(activeIndex + 1)
                    : `${activeIndex + 1}–${lastVisible}`;
            }
        };

        const moveCarousel = (direction) => {
            const step = visibleCount();
            const maximumStart = Math.max(0, cards.length - step);

            if (direction > 0) {
                activeIndex = activeIndex >= maximumStart ? 0 : Math.min(activeIndex + step, maximumStart);
            } else {
                activeIndex = activeIndex <= 0 ? maximumStart : Math.max(0, activeIndex - step);
            }

            updateCarousel();
        };

        previousButton?.addEventListener('click', () => moveCarousel(-1));
        nextButton?.addEventListener('click', () => moveCarousel(1));
        window.addEventListener('resize', updateCarousel, { passive: true });
        window.requestAnimationFrame(updateCarousel);
    });

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

    const cookieConsent = document.querySelector('[data-cookie-consent]');
    const cookieConsentStorageKey = 'angel-touch-cookie-consent-v1';

    if (cookieConsent) {
        const showCookieConsent = () => {
            cookieConsent.hidden = false;
            window.requestAnimationFrame(() => cookieConsent.classList.add('is-visible'));
            cookieConsent.querySelector('[data-cookie-choice="all"]')?.focus({ preventScroll: true });
        };

        const hideCookieConsent = () => {
            cookieConsent.classList.remove('is-visible');
            window.setTimeout(() => {
                cookieConsent.hidden = true;
            }, 250);
        };

        let savedCookieChoice = null;
        try {
            savedCookieChoice = window.localStorage.getItem(cookieConsentStorageKey);
        } catch (error) {
            savedCookieChoice = null;
        }

        if (!savedCookieChoice) {
            showCookieConsent();
        }

        cookieConsent.querySelectorAll('[data-cookie-choice]').forEach((button) => {
            button.addEventListener('click', () => {
                try {
                    window.localStorage.setItem(cookieConsentStorageKey, button.dataset.cookieChoice);
                } catch (error) {
                    // The choice still applies for this page when browser storage is unavailable.
                }

                hideCookieConsent();
            });
        });

        document.querySelectorAll('[data-cookie-settings]').forEach((button) => {
            button.addEventListener('click', showCookieConsent);
        });
    }
})();
