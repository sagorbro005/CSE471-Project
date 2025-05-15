FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    supervisor \
    nginx \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpq-dev \
    libgd-dev \
    libmagickwand-dev \
    imagemagick

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd soap pdo_pgsql
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install node.js
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get install -y nodejs

# Copy application files
COPY . /var/www/html/

# Ensure public directory has proper permissions and copy public images
RUN chmod -R 755 /var/www/html/public
RUN chmod +x /var/www/html/docker/static-images.sh

# Create directories for all required images
RUN mkdir -p /var/www/html/public/images/slider
RUN mkdir -p /var/www/html/public/images/about
RUN mkdir -p /var/www/html/public/images/categories
RUN mkdir -p /var/www/html/public/images/payment

# Set proper permissions for image directories
RUN chmod -R 755 /var/www/html/public/images

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

# Clear Laravel config cache
RUN php artisan config:clear

# Configure the application to work without a database
RUN echo "\nSESSION_DRIVER=file\nCACHE_DRIVER=file\n" >> .env

# For Docker build, create SQLite file (will be replaced by PostgreSQL in production)
RUN mkdir -p /var/www/html/database
RUN touch /var/www/html/database/database.sqlite
RUN chmod 777 /var/www/html/database/database.sqlite

# Set temporary SQLite connection for build
RUN echo "DB_CONNECTION=sqlite\nDB_DATABASE=/var/www/html/database/database.sqlite" >> .env

# Create startup script
COPY ./docker/start.sh /start.sh
RUN chmod +x /start.sh

# Create necessary storage directories and set permissions
RUN mkdir -p /var/www/html/storage/app/public/products
RUN mkdir -p /var/www/html/storage/app/public/sliders
RUN chmod -R 775 /var/www/html/storage
RUN chown -R www-data:www-data /var/www/html/storage

# Make sure public directory is accessible
RUN mkdir -p /var/www/html/public/images
RUN chmod -R 755 /var/www/html/public/images

# Expose port 80
EXPOSE 80

# Use our custom start script
CMD ["/start.sh"]
