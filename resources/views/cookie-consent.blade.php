<div class="cookie-consent" data-cookie-consent hidden>
    <div class="cookie-consent__backdrop" aria-hidden="true"></div>

    <section class="cookie-consent__panel" role="dialog" aria-modal="true" aria-labelledby="cookie-consent-title" aria-describedby="cookie-consent-description" tabindex="-1">
        <header class="cookie-consent__header">
            <span class="cookie-consent__icon" aria-hidden="true">
                <svg viewBox="0 0 48 48" role="presentation">
                    <path d="M40.5 24.7A16.8 16.8 0 1 1 23.3 7.5a9.6 9.6 0 0 0 17.2 8.1 8.8 8.8 0 0 0 0 9.1Z"></path>
                    <circle cx="18" cy="18" r="2.1"></circle>
                    <circle cx="16" cy="29" r="2.1"></circle>
                    <circle cx="27" cy="33" r="2.1"></circle>
                </svg>
            </span>
            <div>
                <p class="cookie-consent__eyebrow">Privacy choices</p>
                <h2 id="cookie-consent-title">Cookies, with your consent.</h2>
            </div>
        </header>

        <div class="cookie-consent__body">
            <p id="cookie-consent-description">We use essential cookies to keep booking and contact forms secure. External YouTube content is only loaded when you choose to play a video.</p>

            <div class="cookie-consent__types" aria-label="Cookie categories">
                <div class="cookie-consent__type">
                    <span aria-hidden="true">✓</span>
                    <div><strong>Essential cookies</strong><small>Required for secure forms and basic website functions.</small></div>
                    <em>Always active</em>
                </div>
                <div class="cookie-consent__type">
                    <span aria-hidden="true">▶</span>
                    <div><strong>External media</strong><small>YouTube only connects after you actively play a video.</small></div>
                    <em>On demand</em>
                </div>
            </div>
        </div>

        <footer class="cookie-consent__footer">
            <div class="cookie-consent__privacy">
                <a href="{{ route('privacy-policy') }}">Read the privacy policy</a>
                <small>You can change your choice later in Cookie settings.</small>
            </div>
            <div class="cookie-consent__actions">
                <button class="cookie-consent__button cookie-consent__button--secondary" type="button" data-cookie-choice="necessary">Use essential only</button>
                <button class="cookie-consent__button cookie-consent__button--primary" type="button" data-cookie-choice="all">Accept all</button>
            </div>
        </footer>
    </section>
</div>
