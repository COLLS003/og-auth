# Use an official PHP runtime as a parent image
FROM php:8.2-cli



# Update package lists and install necessary dependencies
RUN apt-get update -y \
    && apt-get install -y libmcrypt-dev openssl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install additional PHP extensions
# RUN docker-php-ext-install pdo mbstring

# Set the working directory to /app
WORKDIR /app

# Copy the current directory contents into the container at /app
COPY . /app

# Install project dependencies
# RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev --timeout=600

# Install additional PHP extensions including pgsql
RUN apt-get update \
  && apt-get install -y \
  git \
  curl \
  libpng-dev \
  libonig-dev \
  libxml2-dev \
  zip \
  unzip \
  zlib1g-dev \
  libpq-dev \
  libzip-dev

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
  && docker-php-ext-install pdo pdo_pgsql pgsql zip bcmath gd

# Expose port 8000
EXPOSE 8000

# Specify the default command to run on container start
CMD php artisan serve --host=0.0.0.0 --port=8000
