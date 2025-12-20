FROM serversideup/php:8.4-fpm-nginx

COPY --chown=www-data:www-data . /var/www/html

RUN mv env.example.php env.php
