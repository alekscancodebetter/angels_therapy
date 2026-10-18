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
        if (event.key !== 'Escape') return;

        const openDropdown = document.querySelector('[data-admin-dropdown][open]');
        if (openDropdown) {
            openDropdown.removeAttribute('open');
            openDropdown.querySelector('summary')?.focus();
            return;
        }

        close();
    });

    const customerModes = document.querySelectorAll('[data-customer-mode]');
    const customerPanels = document.querySelectorAll('[data-customer-panel]');
    const customerSearch = document.querySelector('[data-customer-search]');
    const customerId = document.querySelector('[data-customer-id]');
    const customerOptions = new Map(
        [...document.querySelectorAll('#admin-customer-options option')].map((option) => [
            option.value.trim().toLocaleLowerCase(),
            option.dataset.customerOptionId,
        ]),
    );

    const syncSelectedCustomer = () => {
        if (!customerSearch || !customerId) return;

        customerId.value = customerOptions.get(customerSearch.value.trim().toLocaleLowerCase()) || '';
        customerSearch.setCustomValidity('');
    };

    const updateCustomerPanels = () => {
        const selectedCustomerMode = [...customerModes].find((field) => field.checked)?.value;
        if (!selectedCustomerMode) return;

        customerPanels.forEach((panel) => {
            const isActive = panel.dataset.customerPanel === selectedCustomerMode;
            panel.hidden = !isActive;

            panel.querySelectorAll('input, select, textarea').forEach((field) => {
                field.disabled = !isActive;

                if (field.hasAttribute('data-customer-required')) {
                    field.required = isActive;
                }

                if (!isActive) {
                    field.setCustomValidity('');
                }
            });
        });

        if (selectedCustomerMode === 'existing') {
            syncSelectedCustomer();
        }
    };

    customerSearch?.addEventListener('input', syncSelectedCustomer);
    customerSearch?.addEventListener('change', syncSelectedCustomer);
    customerModes.forEach((field) => field.addEventListener('change', updateCustomerPanels));
    updateCustomerPanels();

    customerSearch?.form?.addEventListener('submit', (event) => {
        const selectedCustomerMode = [...customerModes].find((field) => field.checked)?.value;
        if (selectedCustomerMode !== 'existing') return;

        syncSelectedCustomer();

        if (!customerId?.value) {
            event.preventDefault();
            customerSearch.setCustomValidity('Please choose a customer from the suggestions.');
            customerSearch.reportValidity();
        }
    });

    const adminDropdowns = [...document.querySelectorAll('[data-admin-dropdown]')];
    const syncAdminDropdown = (dropdown) => {
        const selectedOption = dropdown.querySelector('[data-admin-dropdown-option]:checked');
        const selectedLabel = dropdown.querySelector('[data-admin-dropdown-label]');

        if (selectedLabel) {
            selectedLabel.textContent = selectedOption?.dataset.adminOptionLabel || dropdown.dataset.adminDropdownPlaceholder;
        }
    };

    adminDropdowns.forEach((dropdown) => {
        syncAdminDropdown(dropdown);

        dropdown.addEventListener('toggle', () => {
            if (!dropdown.open) return;

            adminDropdowns.forEach((otherDropdown) => {
                if (otherDropdown !== dropdown) otherDropdown.removeAttribute('open');
            });
        });

        dropdown.querySelectorAll('[data-admin-dropdown-option]').forEach((option) => {
            option.addEventListener('change', () => {
                dropdown.querySelectorAll('input[type="radio"]').forEach((radio) => radio.setCustomValidity(''));
                syncAdminDropdown(dropdown);
                dropdown.removeAttribute('open');
            });
        });
    });

    document.addEventListener('click', (event) => {
        adminDropdowns.forEach((dropdown) => {
            if (dropdown.open && !dropdown.contains(event.target)) dropdown.removeAttribute('open');
        });
    });

    const conflictStatus = document.querySelector('[data-appointment-conflict]');
    const appointmentDate = document.querySelector('[name="appointment_date"]');
    const appointmentTimeOptions = [...document.querySelectorAll('[name="appointment_time"]')];
    const appointmentDurationOptions = [...document.querySelectorAll('[name="duration_minutes"]')];
    const appointmentStatusOptions = [...document.querySelectorAll('[name="status"]')];
    let conflictRequest;

    const selectedOption = (options) => options.find((option) => option.checked);
    const clearAppointmentTimeValidity = () => appointmentTimeOptions.forEach((option) => option.setCustomValidity(''));

    const showConflictStatus = (state, message) => {
        if (!conflictStatus) return;

        conflictStatus.hidden = false;
        conflictStatus.dataset.state = state;
        conflictStatus.textContent = message;
    };

    const hideConflictStatus = () => {
        if (!conflictStatus) return;

        conflictStatus.hidden = true;
        conflictStatus.textContent = '';
        delete conflictStatus.dataset.state;
    };

    const checkAppointmentConflict = async () => {
        if (!conflictStatus || !appointmentDate || !appointmentTimeOptions.length || !appointmentDurationOptions.length || !appointmentStatusOptions.length) return;

        const appointmentTime = selectedOption(appointmentTimeOptions);
        const appointmentDuration = selectedOption(appointmentDurationOptions);
        const appointmentStatus = selectedOption(appointmentStatusOptions);

        conflictRequest?.abort();
        clearAppointmentTimeValidity();

        if (appointmentStatus?.value !== 'scheduled' || !appointmentDate.value || !appointmentTime?.value || !appointmentDuration?.value) {
            hideConflictStatus();
            return;
        }

        conflictRequest = new AbortController();
        const query = new URLSearchParams({
            starts_at: `${appointmentDate.value} ${appointmentTime.value}`,
            duration_minutes: appointmentDuration.value,
        });

        if (conflictStatus.dataset.appointmentId) {
            query.set('appointment_id', conflictStatus.dataset.appointmentId);
        }

        showConflictStatus('checking', 'Checking this time…');

        try {
            const response = await fetch(`${conflictStatus.dataset.conflictUrl}?${query}`, {
                headers: { Accept: 'application/json' },
                cache: 'no-store',
                signal: conflictRequest.signal,
            });

            if (!response.ok) throw new Error('Availability check failed.');

            const result = await response.json();
            const state = result.available ? 'available' : 'conflict';

            appointmentTime.setCustomValidity(result.available ? '' : result.message);
            showConflictStatus(state, result.message);
        } catch (error) {
            if (error.name === 'AbortError') return;

            clearAppointmentTimeValidity();
            showConflictStatus('unavailable', 'The live conflict check is temporarily unavailable. A final check will run when you save.');
        }
    };

    [appointmentDate, ...appointmentTimeOptions, ...appointmentDurationOptions, ...appointmentStatusOptions].forEach((field) => {
        field?.addEventListener('change', checkAppointmentConflict);
    });

    checkAppointmentConflict();

    const requiredFieldMessages = {
        customer_mode: 'Please choose how to add the customer.',
        customer_search: 'Please find and choose a customer.',
        customer_name: 'Please enter the new customer’s name.',
        customer_phone: 'Please enter the new customer’s telephone number.',
        appointment_date: 'Please choose a date.',
        appointment_time: 'Please choose a time.',
        starts_at: 'Please choose a date and time.',
        duration_minutes: 'Please choose a duration.',
        status: 'Please choose a status.',
        name: 'Please enter a name.',
        phone: 'Please enter the customer’s telephone number.',
        password: 'Please enter your password.',
        password_confirmation: 'Please confirm your password.',
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

        if (field.validity.stepMismatch && field.name === 'starts_at') {
            return 'Please choose a time from the list.';
        }

        if (field.validity.stepMismatch) {
            return 'Please choose a valid increment.';
        }

        if (field.validity.badInput) {
            return 'Please enter a valid value.';
        }

        return 'Please check this field.';
    };

    const phoneFields = [...document.querySelectorAll('[data-phone-field]')];
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

    document.querySelectorAll('.admin-form input, .admin-form textarea, .admin-form select').forEach((field) => {
        field.addEventListener('invalid', () => {
            const existingCustomMessage = field.validity.customError ? field.validationMessage : '';
            field.setCustomValidity('');

            const selectedCustomerMode = [...customerModes].find((customerMode) => customerMode.checked)?.value;

            if (field.matches('[data-phone-number]') && field.value.trim() && !applyPhoneValidity(field)) {
                return;
            } else if (field === customerSearch && selectedCustomerMode === 'existing' && !customerId?.value) {
                field.setCustomValidity('Please choose a customer from the suggestions.');
            } else if (existingCustomMessage) {
                field.setCustomValidity(existingCustomMessage);
            } else {
                field.setCustomValidity(englishValidationMessage(field));
            }

            const dropdown = field.closest('[data-admin-dropdown]');
            if (dropdown) {
                dropdown.setAttribute('open', '');
                window.requestAnimationFrame(() => dropdown.querySelector('summary')?.focus());
            }
        });

        const clearValidationMessage = () => {
            if (field.matches('[data-phone-number]')) {
                applyPhoneValidity(field);
                return;
            }

            if (field.type === 'radio') {
                document.querySelectorAll('input[type="radio"]').forEach((radio) => {
                    if (radio.name === field.name) radio.setCustomValidity('');
                });
                return;
            }

            field.setCustomValidity('');
        };
        field.addEventListener('input', clearValidationMessage);
        field.addEventListener('change', clearValidationMessage);
    });
})();
