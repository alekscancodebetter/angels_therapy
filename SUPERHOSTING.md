# Deploy на тестовия сайт в SuperHosting

Тази конфигурация е за **experiment.angeltouchtherapy.co.uk**, потребител `nikkiebl`, бранч `main`.
Подготвена е локално; достъпът до сървъра, наличните PHP версии и настройките на домейна трябва да се проверят в cPanel.

## Какво се разполага

| Път | Предназначение |
| --- | --- |
| `/home/nikkiebl/repositories/angels_therapy` | Съществуващото Git хранилище |
| `/home/nikkiebl/experiment.angeltouchtherapy.co.uk` | Document root: само публични файлове и `index.php` |
| `/home/nikkiebl/angels-therapy-experiment/releases/…` | Частни версии на Laravel с Composer зависимости |
| `/home/nikkiebl/angels-therapy-experiment/current` | Връзка към активната версия |
| `/home/nikkiebl/angels-therapy-experiment/shared/.env` | Пароли, APP_KEY и настройките за тестовия сайт |
| `/home/nikkiebl/angels-therapy-experiment/shared/storage` | Постоянни логове, сесии, файлове и кешове |

Не копирай цялото хранилище в document root. Не качвай `.env`, `vendor`, SQL архиви или инсталационни скриптове там.
Публичният `index.php` се генерира от `superhosting-index.php` и зарежда приложението от частната директория.

## 1. Качи новите файлове в GitHub

От локалната папка `C:\AngelTouchWebsite`, след преглед:

```bash
git add .cpanel.yml .env.superhosting.example deploy-superhosting.sh install-database.sh superhosting-index.php SUPERHOSTING.md
git commit -m "Add SuperHosting experiment deployment"
git push origin main
```

След това в **cPanel → Git Version Control → Manage → Pull or Deploy** на съществуващото хранилище натисни **Update from Remote**.
Провери, че активният бранч е `main`. `.cpanel.yml` трябва да е commit-нат, а работната директория — чиста.
Не редактирай паролите в Git хранилището на хостинга.

## 2. Домейн, HTTPS и защита на тестовия сайт

1. В cPanel → Domains създай/провери `experiment.angeltouchtherapy.co.uk` с document root **`/home/nikkiebl/experiment.angeltouchtherapy.co.uk`**. Не добавяй `/public` към този път при тази конфигурация.
2. Насочи DNS на поддомейна към хостинга и активирай валиден SSL сертификат/AutoSSL. Включи Force HTTPS Redirect, ако е налично.
3. В PHP Manager избери **PHP 8.5** за този поддомейн. CLI и уеб PHP са отделни настройки. Ако 8.5 не е наличен, поискай от SuperHosting подходяща среда; не използвай `--ignore-platform-reqs` и не изпълнявай `composer update` като обходен път.
4. Преди първия deploy включи **Directory Privacy** с потребител/парола за тестовата директория. Остави я включена за тестовия сайт. Приложението има `/admin/setup`, откъдето първият посетител може да създаде администратор, докато още няма такъв.
5. Целевата директория трябва да е предназначена само за този тестов сайт. Ако вече съдържа друг сайт, направи архив и премести старите му файлове извън нея. Запази cPanel настройките за PHP и Directory Privacy в `.htaccess`; премахни конфликтни rewrite правила от предишно приложение.

Deploy скриптът запазва секциите на cPanel в `.htaccess`, обновява собствената си маркирана секция и добавя `X-Robots-Tag: noindex, nofollow, noarchive` и забраняващ `robots.txt`. Това допълва Directory Privacy; не я заменя.

## 3. Създай отделна MySQL база

В **cPanel → MySQL Database Wizard / MySQL Databases**:

1. Създай празна база, например с пълно име `nikkiebl_angeltest`.
2. Създай отделен потребител и силна парола.
3. Добави този потребител към тази база с **ALL PRIVILEGES**.
4. Запиши пълните имена, показани от cPanel, включително префикса. Не използвай базата на реалния сайт.

`install-database.sh` е инсталационният файл за таблиците. Той проверява връзката и изпълнява Laravel миграциите, включително таблиците за клиенти, резервации, администратори, сесии и кеш. Изпълнява се автоматично при deploy и е повторяем: при следващи версии прилага само новите миграции.

Не е необходим SQL импорт в phpMyAdmin. Скриптът не създава cPanel база/потребител, не изпълнява `migrate:fresh`, не изтрива таблици и не зарежда тестовия `DatabaseSeeder`.
Преди обновяване на вече използвана база направи архив през cPanel/phpMyAdmin. Миграциите могат да променят колони и не се отменят автоматично при неуспешен deploy.

## 4. Провери PHP и Composer през SSH / cPanel Terminal

Изпълнявай командите като `nikkiebl`:

```bash
/opt/cpanel/ea-php85/root/usr/bin/php -v
/opt/cpanel/ea-php85/root/usr/bin/php -m
/opt/cpanel/ea-php85/root/usr/bin/php /opt/cpanel/composer/bin/composer --version
command -v git rsync flock tar
```

Нужни са Laravel PHP разширенията, включително `ctype`, `curl`, `dom`, `fileinfo`, `filter`, `hash`, `mbstring`, `openssl`, `pcre`, `PDO`, `pdo_mysql`, `session`, `tokenizer`, `xml`; `zip` или `unzip` за Composer архивите. `composer check-platform-reqs --no-dev` проверява точните изисквания на заключените зависимости. Нужен е изходящ достъп за Composer до Packagist/GitHub.

Ако PHP/Composer са другаде, създай **извън Git** `/home/nikkiebl/.angels-therapy-deploy.conf`:

```bash
PHP_BIN=/opt/cpanel/ea-php85/root/usr/bin/php
COMPOSER_BIN=/opt/cpanel/composer/bin/composer
```

Посочи реалните пътища, потвърдени от поддръжката, и изпълни:

```bash
chmod 600 /home/nikkiebl/.angels-therapy-deploy.conf
```

`COMPOSER_BIN` трябва да сочи към PHP Composer файл/PHAR, не shell wrapper. Ако Composer липсва, поискай поддръжката да го осигури или да потвърди официалния начин за инсталация. Не е необходимо Node/npm за текущите страници: те използват готовите файлове в `public/css`, `public/js` и `public/images`.

Частните директории са с права `700`, `.env` — `600`. PHP процесът трябва да работи като същия cPanel потребител. Ако има `open_basedir`, той трябва да разрешава четене на `/home/nikkiebl/angels-therapy-experiment` и следване на връзките `current`, `.env` и `storage`. Поискай от SuperHosting настройка при отказ за достъп; не използвай `chmod 777`.

## 5. Първо стартиране и .env

В cPanel натисни **Deploy HEAD Commit**, или в Terminal:

```bash
cd /home/nikkiebl/repositories/angels_therapy
/bin/bash deploy-superhosting.sh
```

Първото изпълнение **умишлено спира** след създаване на:

```text
/home/nikkiebl/angels-therapy-experiment/shared/.env
```

Редактирай този файл чрез File Manager (Show Hidden Files) или Terminal:

```dotenv
DB_HOST=localhost
DB_DATABASE=nikkiebl_angeltest
DB_USERNAME=ПЪЛНОТО_ИМЕ_ОТ_CPANEL
DB_PASSWORD="ТВОЯТА_ПАРОЛА"
```

Замени **всички** `CHANGE_ME…` стойности. При специални символи използвай правилно dotenv цитиране; не изпълнявай `.env` чрез `source`. Не изпращай паролата в Git или в чат.

Останалите настройки вече са за HTTPS тестовия адрес: `APP_ENV=production`, `APP_DEBUG=false`, отделна session cookie, `MAIL_MAILER=log` и `QUEUE_CONNECTION=sync`. Изходящи имейли не се изпращат. `APP_KEY` остава празен при първото стартиране; deploy го генерира веднъж и го запазва при обновяване.

Натисни **Deploy HEAD Commit** отново. Скриптът:

1. Създава частна версия от точния локален Git HEAD.
2. Инсталира пакетите от `composer.lock` без dev зависимости и проверява платформата.
3. Включва кратък maintenance режим, инсталира/обновява таблиците и кешира конфигурацията, маршрутите и шаблоните.
4. Копира публичните ресурси и превключва активната версия.
5. Изключва maintenance режима и извежда SHA на публикувания комит.

Ако вече има файл `.deploying` от прекъснат deploy, успешното повторно изпълнение го премахва. При грешка след включване на maintenance сайтът остава с HTTP 503 до успешен повторен deploy. Не махай файла, преди да установиш, че приложението и базата са в съгласувано състояние.

## 6. Проверки и администратор

След като въведеш Directory Privacy паролата в браузъра, провери:

- `https://experiment.angeltouchtherapy.co.uk/up` — HTTP 200 (проверява зареждането на Laravel, не всички функции).
- Началната страница, изображенията, CSS, телефонната връзка и `/treatments/sports-massage`.
- `https://experiment.angeltouchtherapy.co.uk/admin/setup` — създай своя първи администратор. След това страницата връща 404; входът е `/admin/login`.
- Направи тестова резервация с тестови данни и провери, че се вижда в админ панела.
- `/.env` и `/composer.json` не трябва да показват съдържанието на частните файлове.

На този етап проектът не изисква постоянен queue worker или cron за текущите функции. Ако се добавят асинхронни задачи или scheduler, ще трябва отделна конфигурация.

## Следващи обновявания и възстановяване

След push в GitHub: **Update from Remote → Deploy HEAD Commit**. Update сам по себе си не публикува сайта. Не прави Composer install или редакции в клонираното хранилище на хостинга; build се прави в частните версии.

При проблем виж изхода от deploy, `/home/nikkiebl/angels-therapy-experiment/shared/storage/logs/laravel.log` и cPanel логовете в `~/.cpanel/logs/vc_*_git_deploy.log`. Не споделяй пароли или пълни необезличени логове.

Предишните версии се запазват в `releases` и заемат дисково пространство. След успешна проверка може ръчно да премахваш стари неактивни версии, като запазиш текущата и поне една предишна. Не изтривай `shared` и не изтривай директорията, към която сочи `current`.

Смяна само на `current` не е пълен rollback: трябва да се възстановят съответните публични ресурси и, ако миграциите са несъвместими, базата от архив. За връщане на версия предпочети revert commit в `main`, после нормален deploy; прецени отделно съвместимостта на схемата. Скриптът не обещава автоматичен rollback.

## Официални източници

- [SuperHosting: Git deploy през cPanel](https://help.superhosting.bg/git-deploy-cpanel.html)
- [SuperHosting: PHP CLI и пътища до версиите](https://help.superhosting.bg/php-cli.html)
- [cPanel: .cpanel.yml и Update/Deploy](https://docs.cpanel.net/knowledge-base/web-services/guide-to-git-deployment/)
- [Laravel 13: deployment](https://laravel.com/docs/13.x/deployment)
