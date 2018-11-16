FROM php:apache

RUN apt-get update && apt-get upgrade -y && apt-get install r-base -y

COPY raphga_ucsd_edu /var/www/html

EXPOSE 80
