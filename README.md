# Symfony-Library
Object-oriented Virtual Library app using the Symfony 4.4 framework.

## Opis funkcjonalności 
Aplikacja pozwala na przeglądanie oraz wypożyczanie dostępnych książek z możliwością dokonania 
późniejszego zwrotu. Posiadający konto mogą: zarządzać swoim profilem, wypożyczać zasoby, 
monitorować proces rezerwacji. Niezalogowani użytkownicy nie mają możliwości wypożyczania. 
Administrator staje się bibliotekarzem – dodaje, modyfikuje i usuwa elementy znajdujące się na stronie oraz 
decyduje o zatwierdzeniu, bądź odrzuceniu rezerwacji zasobu. 
Wszystkie te możliwości obudowane są w wygodny system zarządzania kontami, a samo 
przeglądanie biblioteki zostało usprawnione poprzez segregację tytułów kategoriami i tagami. Ponadto, 
proces paginacji, oprócz zwiększania wydajności strony, dodatkowo ułatwia odwiedzającym wyszukanie 
interesującej pozycji.

## Funkcje
### Użytkownik niezalogowany:
- założenie konta  
- brak możliwości wypożyczenia zasobu  
### Użytkownik zalogowany:
- zarządzanie swoim kontem (zmiana hasła, modyfikacja danych)  
- przeglądanie biblioteki  
- wyszukiwanie danych książek po tytule, kategorii, tagach  
- możliwość wypożyczenia zasobu i podglądu historii wypożyczeń  
### Administrator:
- posiadanie własnego konta, którym może zarządzać (zmiana hasła, modyfikacja danych)  
- tworzenie, edycja i usuwanie elementów znajdujących się na stronie (książki, kategorie, tagi)  
- podejmowanie decyzji o zatwierdzeniu/odrzuceniu wypożyczenia  
- podgląd aktualnych wypożyczeń użytkowników  
- zarządzanie kontami użytkowników (zmiana hasła, modyfikacja danych)  
- przeglądanie biblioteki  
- wyszukiwanie danych książek po tytule, kategorii, tagach  

## Instalacja na serwerze
- Tworzymy folder, w którym będziemy przetrzymywali naszą aplikację  
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
