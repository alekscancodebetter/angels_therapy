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
        const durationOptions = [...bookingCalendar.querySelectorAll('[data-booking-duration]')];
        const bookingDropdowns = [...bookingCalendar.querySelectorAll('[data-booking-dropdown]')];
        let selectedSlot = bookingCalendar.dataset.selectedSlot || '';
        let selectedDate = selectedSlot.slice(0, 10);
        let conflictSlotValue = '';
        let displayedMonth = bookingCalendar.dataset.initialMonth;
        let displayedCalendar = null;
        let availabilityRequest = null;
        let availabilityRequestNumber = 0;
        let isSubmitting = false;
        let isCalendarStarted = false;

        const syncBookingDropdown = (dropdown) => {
            const selectedOption = dropdown.querySelector('[data-booking-dropdown-option]:checked');
            const selectedLabel = dropdown.querySelector('[data-booking-dropdown-label]');

            if (selectedLabel) {
                selectedLabel.textContent = selectedOption?.dataset.bookingOptionLabel || dropdown.dataset.bookingPlaceholder;
            }
        };

        bookingDropdowns.forEach((dropdown) => {
            syncBookingDropdown(dropdown);

            dropdown.addEventListener('toggle', () => {
                if (!dropdown.open) return;

                bookingDropdowns.forEach((otherDropdown) => {
                    if (otherDropdown !== dropdown) otherDropdown.removeAttribute('open');
                });
            });

            dropdown.querySelectorAll('[data-booking-dropdown-option]').forEach((option) => {
                option.addEventListener('change', () => {
                    dropdown.querySelectorAll('input[type="radio"]').forEach((radio) => radio.setCustomValidity(''));
                    dropdown.removeAttribute('aria-invalid');
                    syncBookingDropdown(dropdown);
                    dropdown.removeAttribute('open');
                });
            });
        });

        document.addEventListener('click', (event) => {
            bookingDropdowns.forEach((dropdown) => {
                if (dropdown.open && !dropdown.contains(event.target)) dropdown.removeAttribute('open');
            });
        });

        document.addEventListener('keydown', (event) => {
            if (event.key !== 'Escape') return;

            const openDropdown = bookingDropdowns.find((dropdown) => dropdown.open);
            if (openDropdown) {
                openDropdown.removeAttribute('open');
                openDropdown.querySelector('summary')?.focus();
            }
        });

        const requiredFieldMessages = {
            treatment_slug: 'Please choose a treatment.',
            duration_minutes: 'Please choose a treatment duration.',
            name: 'Please enter your name.',
            email: 'Please enter your email address.',
            phone: 'Please enter your telephone number.',
            terms_accepted: 'Please accept the Terms & Conditions.',
        };
        const englishValidationMessage = (field) => {
            if (field.validity.valueMissing) {
                return requiredFieldMessages[field.name] || 'Please complete this field.';
            }

            if (field.validity.typeMismatch && field.type === 'email') {
                return 'Please enter a valid email address.';
            }

            if (field.validity.typeMismatch && field.type === 'url') {
                return 'Please enter a valid web address.';
            }

            if (field.validity.tooShort) {
                return `Please use at least ${field.minLength} characters.`;
            }

            if (field.validity.tooLong) {
                return `Please use no more than ${field.maxLength} characters.`;
            }

            if (field.validity.patternMismatch) {
                return 'Please use the requested format.';
            }

            if (field.validity.rangeUnderflow) {
                return `Please enter a value of at least ${field.min}.`;
            }

            if (field.validity.rangeOverflow) {
                return `Please enter a value of no more than ${field.max}.`;
            }

            if (field.validity.stepMismatch) {
                return 'Please choose a valid option.';
            }

            if (field.validity.badInput) {
                return 'Please enter a valid value.';
            }

            return 'Please check this field.';
        };

        const phoneFields = [...bookingCalendar.querySelectorAll('[data-phone-field]')];
        const selectedPhoneCountry = (phoneField) => phoneField.querySelector('[data-phone-country-option]:checked');
        const phoneNationalDigits = (number, countryOption) => {
            const trimmedNumber = number.trim();
            let digits = trimmedNumber.replace(/\D/g, '');
            const dialDigits = countryOption.dataset.phoneDialCode.replace(/\D/g, '');
            const trunkPrefix = countryOption.dataset.phoneTrunkPrefix;

            if (trimmedNumber.startsWith('+') && digits.startsWith(dialDigits)) {
                digits = digits.slice(dialDigits.length);
            } else if (digits.startsWith(`00${dialDigits}`)) {
                digits = digits.slice(dialDigits.length + 2);
            } else if (trunkPrefix && digits.startsWith(trunkPrefix)) {
                digits = digits.slice(trunkPrefix.length);
            }

            return digits;
        };
        const applyPhoneValidity = (numberInput) => {
            const phoneField = numberInput.closest('[data-phone-field]');
            const countryOption = phoneField ? selectedPhoneCountry(phoneField) : null;
            numberInput.setCustomValidity('');

            if (!numberInput.value.trim() || !countryOption) return true;

            const nationalDigits = phoneNationalDigits(numberInput.value, countryOption);
            const minimumDigits = Number(countryOption.dataset.phoneMinDigits);
            const maximumDigits = Number(countryOption.dataset.phoneMaxDigits);
            const usesAllowedCharacters = /^[0-9+\s().-]+$/.test(numberInput.value.trim());
            const isValid = usesAllowedCharacters
                && nationalDigits.length >= minimumDigits
                && nationalDigits.length <= maximumDigits;

            if (!isValid) numberInput.setCustomValidity(countryOption.dataset.phoneMessage);

            return isValid;
        };
        const syncPhoneField = (phoneField) => {
            const countryOption = selectedPhoneCountry(phoneField);
            const numberInput = phoneField.querySelector('[data-phone-number]');
            const helpText = phoneField.querySelector('[data-phone-help]');
            if (!countryOption || !numberInput) return;

            numberInput.placeholder = countryOption.dataset.phonePlaceholder;
            if (helpText) {
                helpText.textContent = `${countryOption.dataset.phoneCountryLabel} ${countryOption.dataset.phoneDialCode}: ${countryOption.dataset.phoneMessage}`;
            }
            applyPhoneValidity(numberInput);
        };

        phoneFields.forEach((phoneField) => {
            syncPhoneField(phoneField);
            phoneField.querySelectorAll('[data-phone-country-option]').forEach((countryOption) => {
                countryOption.addEventListener('change', () => syncPhoneField(phoneField));
            });
        });

        bookingCalendar.querySelectorAll('input, textarea, select').forEach((field) => {
            field.addEventListener('invalid', () => {
                field.setCustomValidity('');

                if (field.matches('[data-phone-number]') && field.value.trim() && !applyPhoneValidity(field)) {
                    return;
                }

                field.setCustomValidity(englishValidationMessage(field));

                const dropdown = field.closest('[data-booking-dropdown]');
                if (dropdown) {
                    dropdown.setAttribute('open', '');
                    window.requestAnimationFrame(() => dropdown.querySelector('summary')?.focus());
                }
            });

            const clearValidationMessage = () => {
                field.setCustomValidity('');
                if (field.matches('[data-phone-number]')) applyPhoneValidity(field);
            };
            field.addEventListener('input', clearValidationMessage);
            field.addEventListener('change', clearValidationMessage);
        });

        const selectedDurationOption = () => durationOptions.find((option) => option.checked);
        const durationValue = () => selectedDurationOption()?.value || '';
        const durationLabel = () => selectedDurationOption()?.dataset.bookingOptionLabel || '';
        const setDurationValue = (duration) => {
            const durationOption = durationOptions.find((option) => option.value === String(duration));
            if (!durationOption) return;

            durationOption.checked = true;
            syncBookingDropdown(durationOption.closest('[data-booking-dropdown]'));
        };

        const resetSelectedSlot = (preserveDate = false) => {
            conflictSlotValue = '';
            const dateToPreserve = preserveDate ? selectedDate : '';
            selectedSlot = '';
            selectedDate = dateToPreserve;
            selectedInput.value = '';
            selectedAppointment.textContent = 'Choose a date and time';
            submitButton.disabled = true;
        };

        const setSelectedSlot = (day, slot) => {
            conflictSlotValue = '';
            timeSlots.querySelector('.booking-alternatives')?.remove();
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

            let alternativeGroup = null;
            let alternativeReasons = null;
            let alternativeOptions = null;

            if (!day.slots.some((slot) => slot.value === conflictSlotValue && !slot.available && slot.suggested_duration_minutes)) {
                conflictSlotValue = '';
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
                    button.addEventListener('click', () => {
                        setSelectedSlot(day, slot);
                        renderTimeSlots(day);
                        timeSlots.querySelector(`button[data-slot="${slot.value}"]`)?.focus({ preventScroll: true });
                    });
                    slotElement = button;
                } else if (offersShorterDuration) {
                    const conflictButton = document.createElement('button');
                    conflictButton.type = 'button';
                    conflictButton.className = 'booking-time booking-time--conflict';
                    conflictButton.dataset.slot = slot.value;
                    conflictButton.textContent = slot.label;
                    conflictButton.setAttribute('aria-label', `${slot.label}: only ${suggestedDuration} minutes available. Show options.`);
                    conflictButton.setAttribute('aria-expanded', String(slot.value === conflictSlotValue));
                    if (slot.value === conflictSlotValue) conflictButton.setAttribute('aria-controls', 'booking-conflict-options');
                    conflictButton.addEventListener('click', () => {
                        resetSelectedSlot(true);
                        conflictSlotValue = slot.value;
                        renderTimeSlots(day);
                        timeSlots.querySelector('.booking-alternatives')?.focus({ preventScroll: true });
                    });
                    slotElement = conflictButton;
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

                if (offersShorterDuration && slot.value === conflictSlotValue) {
                    if (!alternativeGroup) {
                        alternativeGroup = document.createElement('div');
                        alternativeGroup.className = 'booking-alternatives';
                        alternativeGroup.id = 'booking-conflict-options';
                        alternativeGroup.tabIndex = -1;
                        alternativeGroup.setAttribute('role', 'group');
                        alternativeGroup.setAttribute('aria-label', `Appointment options for ${slot.label}`);

                        alternativeReasons = document.createElement('div');
                        alternativeReasons.className = 'booking-alternatives__reasons';

                        const optionsLabel = document.createElement('small');
                        optionsLabel.className = 'booking-alternatives__options-label';
                        optionsLabel.textContent = 'Choose an option';

                        alternativeOptions = document.createElement('div');
                        alternativeOptions.className = 'booking-alternatives__options';
                        alternativeGroup.append(alternativeReasons, optionsLabel, alternativeOptions);
                    }

                    const reasonText = slot.unavailable_reason || 'This time is not available for the selected duration.';
                    const alternativeButton = document.createElement('button');
                    alternativeButton.type = 'button';
                    alternativeButton.className = 'booking-time booking-time--alternative';
                    alternativeButton.dataset.slot = slot.value;
                    alternativeButton.setAttribute('aria-label', `Choose ${suggestedDuration} minutes at ${slot.label}. ${reasonText}`);
                    alternativeButton.setAttribute('aria-pressed', 'false');

                    const alternativeLabel = document.createElement('span');
                    alternativeLabel.className = 'booking-time__label';
                    alternativeLabel.textContent = `Choose ${suggestedDuration} minutes at ${slot.label}`;

                    if (![...alternativeReasons.children].some((reason) => reason.textContent === reasonText)) {
                        const reason = document.createElement('p');
                        reason.className = 'booking-alternatives__reason';
                        reason.textContent = `${slot.label}: ${reasonText}`;
                        alternativeReasons.append(reason);
                    }

                    alternativeButton.append(alternativeLabel);
                    alternativeButton.addEventListener('click', async () => {
                        const targetDate = day.date;
                        const targetSlot = slot.value;
                        alternativeButton.disabled = true;
                        setDurationValue(suggestedDuration);
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
                    alternativeOptions.append(alternativeButton);
                }
            });

            if (alternativeGroup) {
                const fullDurationLabel = document.createElement('small');
                fullDurationLabel.className = 'booking-alternatives__options-label';
                fullDurationLabel.textContent = `Or keep ${durationValue()} minutes at another time`;
                const fullDurationOptions = document.createElement('div');
                fullDurationOptions.className = 'booking-alternatives__full-duration';

                day.slots.filter((slot) => slot.available).forEach((slot) => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'booking-time';
                    button.dataset.slot = slot.value;
                    button.textContent = slot.label;
                    button.setAttribute('aria-label', `Choose ${durationValue()} minutes at ${slot.label}`);
                    button.setAttribute('aria-pressed', 'false');
                    button.addEventListener('click', () => {
                        setSelectedSlot(day, slot);
                        renderTimeSlots(day);
                        timeSlots.querySelector(`button[data-slot="${slot.value}"]`)?.focus({ preventScroll: true });
                    });
                    fullDurationOptions.append(button);
                });

                if (!fullDurationOptions.children.length) {
                    const emptyMessage = document.createElement('p');
                    emptyMessage.textContent = `No ${durationValue()}-minute appointments remain on this day. Please choose another date.`;
                    fullDurationOptions.append(emptyMessage);
                }
                alternativeGroup.append(fullDurationLabel, fullDurationOptions);
                timeSlots.append(alternativeGroup);
            }
        };

        const selectDay = (day) => {
            if (selectedDate !== day.date) {
                conflictSlotValue = '';
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
            const requestedDuration = durationValue();

            bookingCalendar.setAttribute('aria-busy', 'true');
            loadingMessage.hidden = false;
            loadingMessage.textContent = 'Checking available appointments…';
            previousButton.disabled = true;
            nextButton.disabled = true;

            try {
                const url = new URL(availabilityUrl, window.location.origin);
                url.searchParams.set('month', month);
                url.searchParams.set('duration_minutes', durationValue());
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
                    || durationValue() !== requestedDuration) {
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
        durationOptions.forEach((durationOption) => durationOption.addEventListener('change', () => {
            resetSelectedSlot(true);
            loadCalendar(displayedMonth);
        }));
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
