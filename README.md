# Create new Laravel project
composer create-project laravel/laravel corporate-kitchen
cd corporate-kitchen

# Install required packages
composer require laravel/breeze  # for authentication
npm install -D tailwindcss postcss autoprefixer vite
npm install @tailwindcss/forms @tailwindcss/typography


# Delete node_modules and lock file
rm -rf node_modules package-lock.json


php artisan make:migration add_role_phone_address_to_users_table



php artisan make:middleware AdminMiddleware
