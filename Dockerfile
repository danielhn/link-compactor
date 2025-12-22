FROM serversideup/php:8.4-fpm-nginx

LABEL org.opencontainers.image.description="Docker image for Link Compactor, based on the serversideup fpm-nginx image."
LABEL org.opencontainers.image.source=https://github.com/danielhn/link-compactor

COPY --chown=www-data:www-data . /var/www/html

RUN mv env.example.php env.php
