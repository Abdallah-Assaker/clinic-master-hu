#!/bin/bash
set -e

# =============================================
# Clinic Management System - Docker Entrypoint
# =============================================

echo "🏥 Starting Clinic Management System..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to log messages
log_info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

log_warn() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# =============================================
# Wait for Database
# =============================================
wait_for_db() {
    log_info "Waiting for MySQL database to be ready..."
    
    max_attempts=60
    attempt=0
    
    while [ $attempt -lt $max_attempts ]; do
        if php artisan db:monitor --databases=mysql 2>/dev/null | grep -q "OK"; then
            log_info "Database is ready!"
            return 0
        fi
        
        # Alternative check using mysql client
        if mysqladmin ping -h "$DB_HOST" -u "$DB_USERNAME" -p"$DB_PASSWORD" --silent 2>/dev/null; then
            log_info "Database is ready!"
            return 0
        fi
        
        attempt=$((attempt + 1))
        log_warn "Database not ready yet (attempt $attempt/$max_attempts)..."
        sleep 2
    done
    
    log_error "Database connection timeout!"
    return 1
}

# =============================================
# Generate Application Key (if not set)
# =============================================
generate_app_key() {
    # Create .env file from environment variables if it doesn't exist
    if [ ! -f "/var/www/html/.env" ]; then
        log_info "Creating .env file from environment variables..."
        cat > /var/www/html/.env << EOF
APP_NAME=${APP_NAME:-ClinicMaster}
APP_ENV=${APP_ENV:-local}
APP_KEY=${APP_KEY:-}
APP_DEBUG=${APP_DEBUG:-true}
APP_URL=${APP_URL:-http://localhost:8000}
APP_LOCALE=${APP_LOCALE:-ar}
APP_FALLBACK_LOCALE=${APP_FALLBACK_LOCALE:-ar}
APP_FAKER_LOCALE=${APP_FAKER_LOCALE:-ar_SA}

LOG_CHANNEL=${LOG_CHANNEL:-stack}
LOG_STACK=${LOG_STACK:-single}
LOG_LEVEL=${LOG_LEVEL:-debug}

DB_CONNECTION=${DB_CONNECTION:-mysql}
DB_HOST=${DB_HOST:-db}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE:-clinic_master}
DB_USERNAME=${DB_USERNAME:-clinic_user}
DB_PASSWORD=${DB_PASSWORD:-clinic_secret}

SESSION_DRIVER=${SESSION_DRIVER:-redis}
SESSION_LIFETIME=${SESSION_LIFETIME:-120}

BROADCAST_CONNECTION=${BROADCAST_CONNECTION:-log}
FILESYSTEM_DISK=${FILESYSTEM_DISK:-local}
QUEUE_CONNECTION=${QUEUE_CONNECTION:-redis}
CACHE_STORE=${CACHE_STORE:-redis}

REDIS_CLIENT=phpredis
REDIS_HOST=${REDIS_HOST:-redis}
REDIS_PASSWORD=${REDIS_PASSWORD:-null}
REDIS_PORT=${REDIS_PORT:-6379}

MAIL_MAILER=${MAIL_MAILER:-log}
MAIL_HOST=${MAIL_HOST:-127.0.0.1}
MAIL_PORT=${MAIL_PORT:-2525}
MAIL_FROM_ADDRESS=${MAIL_FROM_ADDRESS:-noreply@clinic.local}
MAIL_FROM_NAME="${APP_NAME:-ClinicMaster}"

STRIPE_KEY=${STRIPE_KEY:-}
STRIPE_SECRET=${STRIPE_SECRET:-}
STRIPE_CURRENCY=${STRIPE_CURRENCY:-EGP}

VITE_APP_NAME="${APP_NAME:-ClinicMaster}"
EOF
        chown www-data:www-data /var/www/html/.env
        log_info ".env file created."
    fi
    
    if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
        log_info "Generating application key..."
        php artisan key:generate --force --no-interaction
    else
        log_info "Application key already set."
    fi
}

# =============================================
# Storage Link
# =============================================
setup_storage() {
    log_info "Setting up storage link..."
    
    # Create required directories
    mkdir -p storage/app/public
    mkdir -p storage/framework/{cache,sessions,views}
    mkdir -p storage/logs
    mkdir -p bootstrap/cache
    
    # Set permissions
    chmod -R 775 storage bootstrap/cache
    chown -R www-data:www-data storage bootstrap/cache
    
    # Create storage link if not exists
    if [ ! -L public/storage ]; then
        php artisan storage:link --no-interaction || true
    fi
    
    log_info "Storage setup complete."
}

# =============================================
# Run Migrations
# =============================================
run_migrations() {
    log_info "Running database migrations..."
    
    # Run migrations with retry logic
    max_attempts=3
    attempt=0
    
    while [ $attempt -lt $max_attempts ]; do
        if php artisan migrate --force --no-interaction; then
            log_info "Migrations completed successfully!"
            return 0
        fi
        
        attempt=$((attempt + 1))
        log_warn "Migration attempt $attempt failed, retrying..."
        sleep 5
    done
    
    log_error "Migrations failed after $max_attempts attempts"
    return 1
}

# =============================================
# Run Seeders (First Run Only)
# =============================================
run_seeders() {
    log_info "Checking if seeding is needed..."
    
    # Check if users table has data (indicates already seeded)
    USER_COUNT=$(php artisan tinker --execute="echo \Modules\Users\Entities\User::count();" 2>/dev/null || echo "0")
    
    if [ "$USER_COUNT" = "0" ] || [ -z "$USER_COUNT" ]; then
        log_info "Running database seeders..."
        php artisan db:seed --force --no-interaction
        log_info "Database seeding completed!"
    else
        log_info "Database already seeded (found $USER_COUNT users), skipping..."
    fi
    
    # Always ensure test users exist (Doctor and Patient)
    DOCTOR_EXISTS=$(php artisan tinker --execute="echo \Modules\Users\Entities\User::where('email', 'doctor@clinic.com')->exists() ? '1' : '0';" 2>/dev/null || echo "0")
    
    if [ "$DOCTOR_EXISTS" = "0" ]; then
        log_info "Creating test Doctor and Patient users..."
        php artisan db:seed --class=TestUsersSeeder --force --no-interaction 2>/dev/null || log_warn "TestUsersSeeder not found, skipping..."
    fi
}

# =============================================
# Enable Modules
# =============================================
enable_modules() {
    log_info "Enabling all modules..."
    php artisan module:enable --all --no-interaction || true
    log_info "Modules enabled."
}

# =============================================
# Cache Configuration (Production)
# =============================================
cache_config() {
    if [ "$APP_ENV" = "production" ]; then
        log_info "Caching configuration for production..."
        php artisan config:cache --no-interaction
        php artisan route:cache --no-interaction
        php artisan view:cache --no-interaction
        log_info "Configuration cached."
    else
        log_info "Clearing config cache for development..."
        php artisan config:clear --no-interaction
        php artisan route:clear --no-interaction
        php artisan view:clear --no-interaction
    fi
}

# =============================================
# Create Health Check Endpoint
# =============================================
create_health_endpoint() {
    log_info "Creating health check endpoint..."
    
    mkdir -p public
    cat > public/health << 'EOF'
OK
EOF
    
    log_info "Health check endpoint created."
}

# =============================================
# Main Execution
# =============================================
main() {
    log_info "Environment: $APP_ENV"
    log_info "Debug Mode: $APP_DEBUG"
    
    # Create health endpoint
    create_health_endpoint
    
    # Wait for database
    wait_for_db
    
    # Generate app key
    generate_app_key
    
    # Setup storage
    setup_storage
    
    # Run migrations
    run_migrations
    
    # Run seeders
    run_seeders
    
    # Enable modules
    enable_modules
    
    # Cache config
    cache_config
    
    log_info "✅ Clinic Management System is ready!"
    log_info "🌐 Access the application at: ${APP_URL:-http://localhost:8000}"
    log_info "📧 Login Credentials:"
    log_info "   Admin:   admin@clinic.com / password"
    log_info "   Doctor:  doctor@clinic.com / password"
    log_info "   Patient: patient@clinic.com / password"
    
    # Execute the main command
    exec "$@"
}

# Run main function
main "$@"
