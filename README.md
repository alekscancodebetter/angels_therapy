# Angel Touch Therapy

A bespoke, English-language Laravel website for Angel Touch Therapy in Harrogate. The public page includes a visual treatment grid, individual pages for all four massage types, areas of treatment, therapist information, qualifications, client testimonials, video reviews, opening hours and contact details.

## Stack

- PHP 8.3+
- Laravel 13
- MySQL for production data
- Custom Blade, HTML, CSS and vanilla JavaScript

## Local setup

1. Copy `.env.example` to `.env`.
2. Create a MySQL database named `angel_touch_therapy` and update the database credentials in `.env`.
3. Run `composer install` and `php artisan key:generate`.
4. Run `php artisan migrate`.
5. Run `php artisan serve` and open `http://127.0.0.1:8000`.

Appointment requests are validated and stored in the `appointment_requests` table. Configure the `MAIL_*` settings if email notifications are added later.

Treatment copy is managed centrally in `config/treatments.php`. Price and duration fields are intentionally marked as “To be confirmed” until the final details are supplied.
