#!/bin/bash

echo "🚀 Starting Deployment Preparation..."

# 1. Export Database
echo "📦 Exporting database..."
mysqldump -u root -pcollins123 campus_mall > database.sql
echo "✅ Database exported to database.sql"

# 2. Optimization
echo "⚡ Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "✅ Optimization complete."

# 3. Handle Storage
echo "📂 Ensuring storage files are in public/storage..."
mkdir -p public/storage
cp -r storage/app/public/* public/storage/ 2>/dev/null || true
echo "✅ Storage files moved."

# 4. Create Zip
echo "🤐 Creating deployment.zip..."
zip -r deployment.zip . -x "*.git*" "node_modules/*" "tests/*" "storage/logs/*" ".env" "*.zip" "prepare_deployment.sh"
echo "✅ deployment.zip created!"

echo "🏁 All set! Follow these steps on InfinityFree:"
echo "1. Upload 'deployment.zip' to htdocs and extract it."
echo "2. Import 'database.sql' into your InfinityFree MySQL via phpMyAdmin."
echo "3. Manually create a .env file on the server with your production settings."
echo "4. Use the .htaccess file already in the root for public folder redirection."
