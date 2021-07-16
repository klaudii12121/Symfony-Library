# Symfony-Library
Object-oriented Virtual Library app using the Symfony 4.4 framework.

## Instalacja na serwerze
- Tworzymy folder, w którym będziemy przetrzymywali naszą aplikację: 'Library'
- Usuwamy plik .env.local
- W pliku .env ustawiamy paramtetry dostępowe do bazy danych,
- W katalogu app/public tworzymy plik .htaccess o następującej zawartości:
```text
<IfModule mod_rewrite.c>
    Options -MultiViews
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>

<IfModule !mod_rewrite.c>
    <IfModule mod_alias.c>
        RedirectMatch 302 ^/$ /index.php/
    </IfModule>
</IfModule>
```
- W katalogu projektu (/app) wykonujemy:
```text
composer install
composer update
```
- nadajemy uprawnienia folderom vendor oraz var
```text
chmod 777 vendor
chmod 77 var
```
- W katalogu projektu na serwerze (w public_html) wykonujemy link symboliczny do naszej aplikacji:
```text
ln -s ~/Library/app/public LibraryProject
```
- ładujemy migracje bazodanowe i zapełniamy bazę danych losowymi danymi:
```text
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```
