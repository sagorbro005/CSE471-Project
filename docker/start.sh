#!/bin/bash

# Clear config cache to ensure we use environment variables from Render
php artisan config:clear

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

# Ensure storage directory is writable
chmod -R 775 /var/www/html/storage
chown -R www-data:www-data /var/www/html/storage

# Create ALL required image directories (with their full paths)
echo "Setting up static image directories..."
mkdir -p /var/www/html/public/images/slider
mkdir -p /var/www/html/public/images/about
mkdir -p /var/www/html/public/images/categories
mkdir -p /var/www/html/public/images/payment

# Copy any existing image files from code repository (if they exist)
echo "Copying static images from repository if they exist..."
# This ensures any images committed to the repo are used
cp -r /var/www/html/public/images/* /var/www/html/public/images/ 2>/dev/null || :

# Ensure public directory has correct permissions
chmod -R 755 /var/www/html/public
chown -R www-data:www-data /var/www/html/public

# Create default placeholder images for important paths if they don't exist
if [ ! -f "/var/www/html/public/images/slider/slide1.jpg" ]; then
  echo "Creating placeholder slider images"
  convert -size 800x400 xc:lightblue -pointsize 30 -gravity center -annotate 0 "Slider 1" /var/www/html/public/images/slider/slide1.jpg || :
  convert -size 800x400 xc:lightgreen -pointsize 30 -gravity center -annotate 0 "Slider 2" /var/www/html/public/images/slider/slide2.jpg || :
  convert -size 800x400 xc:lightpink -pointsize 30 -gravity center -annotate 0 "Slider 3" /var/www/html/public/images/slider/slide3.jpg || :
fi

# Create category placeholder images
for cat in medicine healthcare lab-test wellness; do
  if [ ! -f "/var/www/html/public/images/categories/${cat}.jpg" ]; then
    echo "Creating placeholder image for ${cat}"
    convert -size 400x300 xc:beige -pointsize 24 -gravity center -annotate 0 "${cat}" /var/www/html/public/images/categories/${cat}.jpg || :
  fi
done

# Create placeholder payment method logos
for pay in visa mastercard amex bkash nagad rocket; do
  if [ ! -f "/var/www/html/public/images/${pay}.png" ]; then
    echo "Creating placeholder image for ${pay}"
    convert -size 200x100 xc:white -pointsize 20 -gravity center -annotate 0 "${pay}" /var/www/html/public/images/${pay}.png || :
  fi
done

# Create placeholder team images
for i in 1 2 3; do
  if [ ! -f "/var/www/html/public/images/about/team${i}.jpg" ]; then
    echo "Creating placeholder team ${i} image"
    convert -size 300x300 xc:gray -pointsize 24 -gravity center -annotate 0 "Team Member ${i}" /var/www/html/public/images/about/team${i}.jpg || :
  fi
done

# Create pharmacy placeholder
if [ ! -f "/var/www/html/public/images/about/pharmacy.jpg" ]; then
  echo "Creating placeholder pharmacy image"
  convert -size 800x500 xc:lightgray -pointsize 30 -gravity center -annotate 0 "Pharmacy" /var/www/html/public/images/about/pharmacy.jpg || :
fi

# Create default/placeholder images
if [ ! -f "/var/www/html/public/images/placeholder.png" ]; then
  echo "Creating placeholder images"
  convert -size 300x300 xc:whitesmoke -pointsize 20 -gravity center -annotate 0 "Placeholder" /var/www/html/public/images/placeholder.png || :
fi
if [ ! -f "/var/www/html/public/images/no-image.png" ]; then
  cp /var/www/html/public/images/placeholder.png /var/www/html/public/images/no-image.png 2>/dev/null || :
fi
if [ ! -f "/var/www/html/public/images/default.png" ]; then
  cp /var/www/html/public/images/placeholder.png /var/www/html/public/images/default.png 2>/dev/null || :
fi

echo "Static images setup complete"

# Start supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
