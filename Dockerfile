FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    nginx \
    supervisor

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd soap
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install node.js
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get install -y nodejs

# Copy application files
COPY . /var/www/html/

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Install dependencies
RUN composer install --optimize-autoloader --no-dev
RUN npm install
RUN npm run build

# Configure Nginx
COPY ./docker/nginx.conf /etc/nginx/sites-available/default

# Configure Supervisor
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set up environment file
RUN cp .env.example .env || echo "No .env.example found, creating blank .env" && touch .env
RUN php artisan key:generate

# Create SQLite database directory and file
RUN mkdir -p /var/www/html/database
RUN touch /var/www/html/database/database.sqlite
RUN chown -R www-data:www-data /var/www/html/database

# Expose port 80
EXPOSE 80

# Start services with Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
