#!/usr/bin/env bash
cd /var/www
/usr/local/bin/composer.phar install
/sbin/my_init
