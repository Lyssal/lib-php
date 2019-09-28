install-dev:
	composer install

phpunit:
	./vendor/bin/phpunit --bootstrap tests/autoload.php tests

phpdoc:
	phpdoc -c doc/phpdoc.tpl.xml
