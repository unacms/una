ABOUT
=====

Unit test for Trident.

INSTALLATION
============

1. Get composer.

http://getcomposer.org/doc/00-intro.md#installation-nix


2. Install dependences.

Run the following from `/tests/` folder:
```
composer.phar install
```
or:
```
composer install
```

Alternatively run the following from the script root folder:
```
phing install
```


USING
=====

Run the following command from `/tests/` folder after installation:
```
./vendor/bin/phpunit 
```
or:
```
/path/to/bin/php ./vendor/bin/phpunit
```

Alternatively run the following from the script root folder:
```
phing test
```

