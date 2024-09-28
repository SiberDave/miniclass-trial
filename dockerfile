# docker build -t miniclass:0.1 .
FROM php:8.3-fpm
COPY miniclass /var/www/html
RUN docker-php-ext-install mysqli 
RUN docker-php-ext-enable mysqli
