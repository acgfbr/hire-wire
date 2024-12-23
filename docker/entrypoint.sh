#!/bin/sh

echo "Waiting for MariaDB to be ready..."

# Initial delay to allow MariaDB to initialize
echo "Waiting initial delay for MariaDB initialization..."
sleep 10

MAX_TRIES=30
TRIES=0

while true; do
    TRIES=$((TRIES+1))
    
    if [ $TRIES -gt $MAX_TRIES ]; then
        echo "Error: MariaDB did not become ready in time"
        exit 1
    fi
    
    echo "Attempt $TRIES of $MAX_TRIES: Checking MariaDB connection..."
    
    # Try basic connection and capture error
    if mariadb -h "db" -u "hire_wire_user" -p"password" --skip-ssl -e "SELECT 1" hire_wire; then
        echo "MariaDB is ready!"
        break
    else
        echo "MariaDB not ready yet, waiting..."
        sleep 2
    fi
done

# Clear Laravel caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate
fi

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Create Passport client
echo "Creating Passport client..."
php artisan passport:client --personal --no-interaction

# Create log directory for supervisor
mkdir -p /var/log/supervisor

# Start supervisor
echo "Starting supervisor..."
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
