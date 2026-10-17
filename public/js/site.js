(() => {
    const header = document.querySelector('[data-header]');
    const menuToggle = document.querySelector('[data-menu-toggle]');
    const nav = document.querySelector('#main-nav');
    const desktopHover = window.matchMedia('(hover: hover) and (pointer: fine)');
    const opensOnHover = () => desktopHover.matches && window.innerWidth > 820;
    const isMobileNavigation = () => window.innerWidth <= 820;

    const updateHeader = () => header?.classList.toggle('is-scrolled', window.scrollY > 24);
    let headerUpdatePending = false;
    const requestHeaderUpdate = () => {
        if (headerUpdatePending) return;

        headerUpdatePending = true;
        window.requestAnimationFrame(() => {
            updateHeader();
            headerUpdatePending = false;
        });
    };
    updateHeader();
    window.addEventListener('scroll', requestHeaderUpdate, { passive: true });

    const updateMenuLabel = (isOpen) => {
        const menuLabel = menuToggle?.querySelector('[data-menu-label]');
        if (menuLabel) menuLabel.textContent = isOpen ? 'Close menu' : 'Open menu';
    };

    const closeMenu = (restoreFocus = false) => {
        if (!menuToggle || !nav) return;
        menuToggle.setAttribute('aria-expanded', 'false');
        updateMenuLabel(false);
        nav.classList.remove('is-open');
        nav.querySelectorAll('.nav-dropdown[open]').forEach((dropdown) => dropdown.removeAttribute('open'));
        document.body.classList.remove('menu-open');
        if (restoreFocus) menuToggle.focus({ preventScroll: true });
    };

    menuToggle?.addEventListener('click', () => {
        const open = menuToggle.getAttribute('aria-expanded') === 'true';
        menuToggle.setAttribute('aria-expanded', String(!open));
        updateMenuLabel(!open);
        nav?.classList.toggle('is-open', !open);
        document.body.classList.toggle('menu-open', !open);

        if (!open) {
            window.requestAnimationFrame(() => nav?.querySelector('summary, a[href]')?.focus({ preventScroll: true }));
        }
    });

    nav?.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => closeMenu()));

    nav?.querySelectorAll('.nav-dropdown').forEach((dropdown) => {
        let closeTimer;

        dropdown.addEventListener('mouseenter', () => {
            if (!opensOnHover()) return;

            window.clearTimeout(closeTimer);
            dropdown.setAttribute('open', '');
        });

        dropdown.addEventListener('mouseleave', () => {
            if (!opensOnHover()) return;

            closeTimer = window.setTimeout(() => dropdown.removeAttribute('open'), 180);
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
        if (event.key === 'Escape') {
            const openDropdown = nav?.querySelector('.nav-dropdown[open]');
            if (openDropdown) {
                openDropdown.removeAttribute('open');
                openDropdown.querySelector('summary')?.focus();
                return;
            }

            if (isMobileNavigation() && nav?.classList.contains('is-open')) {
                closeMenu(true);
            }
        }

        if (event.key !== 'Tab' || !isMobileNavigation() || !nav?.classList.contains('is-open') || !menuToggle) return;

        const focusableElements = [
            menuToggle,
            ...nav.querySelectorAll('summary, a[href]'),
        ].filter((element) => element.getClientRects().length > 0);
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];

        if (event.shiftKey && document.activeElement === firstElement) {
            event.preventDefault();
            lastElement.focus();
        } else if (!event.shiftKey && document.activeElement === lastElement) {
            event.preventDefault();
            firstElement.focus();
        }
    });

    window.addEventListener('resize', () => {
        if (!isMobileNavigation() && nav?.classList.contains('is-open')) closeMenu();
    }, { passive: true });

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
        const durationSelect = bookingCalendar.querySelector('[data-booking-duration]');
        let selectedSlot = bookingCalendar.dataset.selectedSlot || '';
        let selectedDate = selectedSlot.slice(0, 10);
        let displayedMonth = bookingCalendar.dataset.initialMonth;
        let displayedCalendar = null;
        let availabilityRequest = null;
        let availabilityRequestNumber = 0;
        let isSubmitting = false;
        let isCalendarStarted = false;

        const requiredFieldMessages = {
            treatment_slug: 'Please choose a treatment.',
            duration_minutes: 'Please choose a treatment duration.',
            name: 'Please enter your name.',
            email: 'Please enter your email address.',
            phone: 'Please enter your telephone number.',
            terms_accepted: 'Please accept the Terms & Conditions.',
        };

        bookingCalendar.querySelectorAll('[required]').forEach((field) => {
            field.addEventListener('invalid', () => {
                field.setCustomValidity('');

                if (field.validity.valueMissing) {
                    field.setCustomValidity(requiredFieldMessages[field.name] || 'Please complete this field.');
                } else if (field.validity.typeMismatch && field.type === 'email') {
                    field.setCustomValidity('Please enter a valid email address.');
                }
            });

            const clearValidationMessage = () => field.setCustomValidity('');
            field.addEventListener('input', clearValidationMessage);
            field.addEventListener('change', clearValidationMessage);
        });

        const durationLabel = () => durationSelect?.selectedOptions[0]?.textContent || '';

        const resetSelectedSlot = () => {
            selectedSlot = '';
            selectedDate = '';
            selectedInput.value = '';
            selectedAppointment.textContent = 'Choose a date and time';
            submitButton.disabled = true;
        };

        const setSelectedSlot = (day, slot) => {
            selectedSlot = slot.value;
            selectedDate = day.date;
            selectedInput.value = slot.value;
            selectedAppointment.textContent = `${day.label} at ${slot.label} · ${durationLabel()}`;
            submitButton.disabled = false;

            timeSlots.querySelectorAll('button.booking-time').forEach((button) => {
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
                const suggestedDuration = Number(slot.suggested_duration_minutes) || null;
                const offersShorterDuration = !slot.available && suggestedDuration !== null;
                let slotElement;

                if (slot.available) {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'booking-time';
                    button.dataset.slot = slot.value;
                    button.setAttribute('aria-label', slot.label);
                    button.setAttribute('aria-pressed', String(slot.value === selectedSlot));
                    button.textContent = slot.label;
                    button.addEventListener('click', () => setSelectedSlot(day, slot));
                    slotElement = button;
                } else {
                    const unavailableSlot = document.createElement('span');
                    unavailableSlot.className = 'booking-time booking-time--unavailable';
                    unavailableSlot.textContent = slot.label;
                    unavailableSlot.setAttribute('aria-label', `${slot.label}. ${slot.unavailable_reason || 'Unavailable.'}`);
                    slotElement = unavailableSlot;
                }

                if (slot.available && slot.value === selectedSlot) {
                    slotElement.classList.add('is-selected');
                    selectedAppointment.textContent = `${day.label} at ${slot.label} · ${durationLabel()}`;
                    submitButton.disabled = false;
                }

                timeSlots.append(slotElement);

                if (offersShorterDuration) {
                    const alternativeButton = document.createElement('button');
                    alternativeButton.type = 'button';
                    alternativeButton.className = 'booking-time booking-time--alternative';
                    alternativeButton.dataset.slot = slot.value;
                    alternativeButton.setAttribute('aria-label', `Choose ${suggestedDuration} minutes at ${slot.label}. ${slot.unavailable_reason}`);
                    alternativeButton.setAttribute('aria-pressed', 'false');

                    const alternativeLabel = document.createElement('span');
                    alternativeLabel.className = 'booking-time__label';
                    alternativeLabel.textContent = `Choose ${suggestedDuration} minutes at ${slot.label}`;

                    const reason = document.createElement('small');
                    reason.className = 'booking-time__reason';
                    reason.textContent = slot.unavailable_reason;

                    alternativeButton.append(alternativeLabel, reason);
                    alternativeButton.addEventListener('click', async () => {
                        const targetDate = day.date;
                        const targetSlot = slot.value;
                        alternativeButton.disabled = true;
                        durationSelect.value = String(suggestedDuration);
                        resetSelectedSlot();

                        const calendarLoaded = await loadCalendar(displayedMonth);
                        const refreshedDay = displayedCalendar?.days.find((calendarDay) => calendarDay.date === targetDate);
                        const refreshedSlot = refreshedDay?.slots.find((calendarSlot) => (
                            calendarSlot.value === targetSlot && calendarSlot.available
                        ));

                        if (calendarLoaded && refreshedDay && refreshedSlot) {
                            selectDay(refreshedDay);
                            setSelectedSlot(refreshedDay, refreshedSlot);
                        } else if (calendarLoaded) {
                            loadingMessage.hidden = false;
                            loadingMessage.textContent = 'That time is no longer available. Please choose another appointment.';
                        }
                    });
                    timeSlots.append(alternativeButton);
                }
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
            displayedMonth = calendar.month;
            displayedCalendar = calendar;
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
                const availableCount = day.slots.filter((slot) => (
                    slot.available || slot.suggested_duration_minutes
                )).length;
                button.type = 'button';
                button.className = 'booking-day';
                button.dataset.date = day.date;
                button.textContent = day.day;
                button.disabled = availableCount === 0;
                button.setAttribute('aria-label', availableCount
                    ? `${day.label}, ${availableCount} booking options available`
                    : `${day.label}, no appointments available`);
                button.setAttribute('aria-pressed', String(day.date === selectedDate));

                if (day.date === selectedDate && availableCount) {
                    button.classList.add('is-selected');
                }

                button.addEventListener('click', () => selectDay(day));
                daysGrid.append(button);
            });

            const selectedDay = calendar.days.find((day) => (
                day.date === selectedDate
                && day.slots.some((slot) => slot.available || slot.suggested_duration_minutes)
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
            availabilityRequest?.abort();
            availabilityRequest = new AbortController();
            const requestNumber = ++availabilityRequestNumber;
            const requestedDuration = durationSelect.value;

            bookingCalendar.setAttribute('aria-busy', 'true');
            loadingMessage.hidden = false;
            loadingMessage.textContent = 'Checking available appointments…';
            previousButton.disabled = true;
            nextButton.disabled = true;

            try {
                const url = new URL(availabilityUrl, window.location.origin);
                url.searchParams.set('month', month);
                url.searchParams.set('duration_minutes', durationSelect.value);
                const response = await fetch(url, {
                    headers: { Accept: 'application/json' },
                    credentials: 'same-origin',
                    cache: 'no-store',
                    signal: availabilityRequest.signal,
                });

                if (!response.ok) throw new Error('Availability could not be loaded.');

                const calendar = await response.json();
                if (requestNumber !== availabilityRequestNumber
                    || String(calendar.duration_minutes) !== requestedDuration
                    || durationSelect.value !== requestedDuration) {
                    return false;
                }

                renderCalendar(calendar);
                loadingMessage.hidden = true;
                return true;
            } catch (error) {
                if (error.name !== 'AbortError' && requestNumber === availabilityRequestNumber) {
                    loadingMessage.textContent = 'The calendar could not be loaded. Please refresh the page or call Villy.';
                }

                return false;
            } finally {
                if (requestNumber === availabilityRequestNumber) {
                    bookingCalendar.setAttribute('aria-busy', 'false');
                    availabilityRequest = null;
                }
            }
        };

        const startCalendar = () => {
            if (isCalendarStarted) return;

            isCalendarStarted = true;
            loadCalendar(bookingCalendar.dataset.initialMonth);
            window.setInterval(() => {
                if (!document.hidden && !isSubmitting) loadCalendar(displayedMonth);
            }, 60000);
        };

        previousButton.addEventListener('click', () => {
            if (previousButton.dataset.month) loadCalendar(previousButton.dataset.month);
        });
        nextButton.addEventListener('click', () => {
            if (nextButton.dataset.month) loadCalendar(nextButton.dataset.month);
        });
        durationSelect.addEventListener('change', () => {
            resetSelectedSlot();
            loadCalendar(displayedMonth);
        });
        bookingCalendar.addEventListener('submit', async (event) => {
            if (isSubmitting || !selectedSlot) return;

            event.preventDefault();
            const slotBeforeRefresh = selectedSlot;
            submitButton.disabled = true;

            const calendarLoaded = await loadCalendar(displayedMonth);
            if (!calendarLoaded) {
                submitButton.disabled = !selectedSlot;
                return;
            }

            if (selectedSlot !== slotBeforeRefresh) {
                loadingMessage.hidden = false;
                loadingMessage.textContent = 'That time is no longer available. Please choose another appointment.';
                return;
            }

            isSubmitting = true;
            bookingCalendar.requestSubmit();
        });
        document.addEventListener('visibilitychange', () => {
            if (isCalendarStarted && !document.hidden && !isSubmitting) loadCalendar(displayedMonth);
        });
        bookingCalendar.addEventListener('focusin', startCalendar, { once: true });
        bookingCalendar.addEventListener('pointerdown', startCalendar, { once: true, passive: true });

        if ('IntersectionObserver' in window) {
            const calendarObserver = new IntersectionObserver((entries) => {
                if (!entries.some((entry) => entry.isIntersecting)) return;

                startCalendar();
                calendarObserver.disconnect();
            }, { rootMargin: '600px 0px' });
            calendarObserver.observe(bookingCalendar);
        } else {
            startCalendar();
        }

        if (window.location.hash === '#booking'
            || selectedSlot
            || bookingCalendar.querySelector('[aria-invalid="true"]')) {
            startCalendar();
        }
    }

    document.querySelector('[data-booking-errors]')?.focus({ preventScroll: false });

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
            carousel.classList.toggle('has-no-overflow', cards.length <= visibleCards);

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
            const videoCard = button.closest('[data-video-card]');
            if (!videoCard || videoCard.querySelector('iframe')) return;
            const videoId = button.dataset.video;
            const iframe = document.createElement('iframe');
            iframe.src = `https://www.youtube-nocookie.com/embed/${videoId}?autoplay=1&rel=0`;
            iframe.title = button.getAttribute('aria-label') || 'Video review';
            iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
            iframe.allowFullscreen = true;
            iframe.tabIndex = 0;
            button.replaceWith(iframe);
            iframe.focus({ preventScroll: true });
        });
    });

    const cookieConsent = document.querySelector('[data-cookie-consent]');
    const cookieConsentStorageKey = 'angel-touch-cookie-consent-v1';

    if (cookieConsent) {
        const cookieConsentPanel = cookieConsent.querySelector('[role="dialog"]');
        const necessaryCookiesButton = cookieConsent.querySelector('[data-cookie-choice="necessary"]');
        const backgroundElements = [...document.body.children].filter((element) => (
            element !== cookieConsent && element.tagName !== 'SCRIPT'
        ));
        let restoreFocusElement = null;

        const showCookieConsent = (trigger = null) => {
            restoreFocusElement = trigger instanceof HTMLElement ? trigger : null;
            cookieConsent.hidden = false;
            document.body.classList.add('cookie-consent-open');
            backgroundElements.forEach((element) => { element.inert = true; });
            window.requestAnimationFrame(() => {
                cookieConsent.classList.add('is-visible');
                cookieConsentPanel?.focus({ preventScroll: true });
            });
        };

        const hideCookieConsent = () => {
            cookieConsent.classList.remove('is-visible');
            document.body.classList.remove('cookie-consent-open');
            backgroundElements.forEach((element) => { element.inert = false; });
            window.setTimeout(() => {
                cookieConsent.hidden = true;
                restoreFocusElement?.focus({ preventScroll: true });
                restoreFocusElement = null;
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
            button.addEventListener('click', () => showCookieConsent(button));
        });

        document.addEventListener('keydown', (event) => {
            if (cookieConsent.hidden) return;

            if (event.key === 'Escape') {
                event.preventDefault();
                necessaryCookiesButton?.click();
                return;
            }

            if (event.key !== 'Tab') return;

            const focusableElements = [...cookieConsent.querySelectorAll('a[href], button:not([disabled])')];
            if (!focusableElements.length) return;

            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];

            if (event.shiftKey && document.activeElement === firstElement) {
                event.preventDefault();
                lastElement.focus();
            } else if (!event.shiftKey && document.activeElement === lastElement) {
                event.preventDefault();
                firstElement.focus();
            }
        });
    }
})();
