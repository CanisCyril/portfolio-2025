# Use the official PHP 8.3 FPM image as the base
FROM php:8.3-fpm

# Define build arguments for custom user setup
ARG user
ARG uid

# Update package list and install required packages
# Includes tools and development libraries for PHP extensions
RUN apt update && apt install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev

# Clean up package lists to reduce image size
RUN apt clean && rm -rf /var/lib/apt/lists/*

# Install essential PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd

# Install Redis extension with required build tools
RUN apt update && apt install -y \
    libz-dev \
    build-essential \
    autoconf \
    && pecl install redis \
    && docker-php-ext-enable redis

# Copy Composer from the official Composer image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create a new user with a specific UID and add to groups
RUN useradd -G www-data,root -u $uid -d /home/$user $user

# Set up Composer directory for the new user and assign correct permissions
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set the working directory inside the container
WORKDIR /var/www

# Use the non-root user for running container processes
USER $user
 