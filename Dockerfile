FROM php:8-alpine3.20
RUN docker-php-ext-install mysqli
WORKDIR /app

CMD ["php", "-S", "localhost:8000"]
