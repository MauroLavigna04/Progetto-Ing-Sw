FROM php:8.2-apache

# installo tutto ciò che è possibile per fare la connessione al database per far funzionare quindi il comando mysqli di php
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

RUN a2enmod rewrite

# Installa l'estensione mysqli
