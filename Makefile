SHELL := /bin/bash
DATA := $(CURDIR)/data
ENV := $(CURDIR)/env
APP := $(CURDIR)/app
LIB := $(CURDIR)/vendor
VER := php-5.5.9
MIRROR := us3.php.net
PATH := $(ENV)/bin:$(LIB)/bin:$(PATH)


all: deps database test run

deps: | $(ENV)/bin/composer $(ENV)/activate
	composer update

database: $(DATA)/store.sqlite3

$(DATA)/store.sqlite3:
	phinx migrate

$(ENV):
	mkdir -p $(ENV)/src $(ENV)/share/man/man1 $(ENV)/share/man/man5

$(ENV)/activate: | $(ENV)
	echo "export PATH=$(ENV)/bin:$(LIB)/bin:\$$PATH" > $(ENV)/activate

$(ENV)/src/$(VER): | $(ENV)
	curl -L http://$(MIRROR)/get/$(VER).tar.bz2/from/this/mirror -o - | \
	tar -C $(ENV)/src -xvjf -

$(ENV)/lib/php.ini: | $(ENV)/src/$(VER)
	mkdir -p $(ENV)/lib
	sed -e 's/^file_uploads = On/file_uploads = Off/' \
		  -e 's/^;date\.timezone =/date.timezone = "America\/Chicago"/' \
			$(ENV)/src/$(VER)/php.ini-development > $(ENV)/lib/php.ini

$(ENV)/bin/php: | $(ENV)/src/$(VER) $(ENV)/lib/php.ini
	cd $(ENV)/src/$(VER) && \
	./configure --without-iconv --with-openssl --prefix=$(ENV) && \
	make install

$(ENV)/bin/composer: | $(ENV)/bin/php
	curl -s http://getcomposer.org/installer | \
			php -- --install-dir=$(ENV)/bin
	ln -s $(ENV)/bin/composer.phar $(ENV)/bin/composer

clean:
	rm -rf composer.lock vendor/ data/store.sqlite3

distclean: clean
	rm -rf $(ENV)

syntax-check:
	find $(APP) -iname '*.php' -exec php -d error_reporting=32767 -l {} \;

style-check:
	phpcs --standard=PSR2 $(APP)

test: syntax-check style-check
	phpunit

run:
	php -t $(APP) -S 127.0.0.1:8123 $(APP)/index.php


.PHONY: run test style-check syntax-check distclean clean database deps all
