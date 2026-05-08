#!/bin/bash

echo "🚀 Initializing Production Database..."

# Run migrations
echo "📦 Running database migrations..."
php artisan migrate --force

# Seed the database if needed
echo "🌱 Seeding database..."
php artisan db:seed --force

echo "✅ Database initialization complete!"