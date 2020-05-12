# SYSTEM MANAGEMENT LAUNDRY
An Aplication laundry Made with Laravel + VueJS

# INSTALLATION
Use the package manager (composer and npm) for installing
1. Do the following commands for installing
```bash
git clone https://github.com/rizalpahlevii/laundry-laravue.git
cd laundry-laravue
composer install
npm install
copy .env.example .env
php artisan key:generate
```

2. Create a database **laravel_vue** 
3. Setting database name, username, and password in your .env file
4. Do the following instructions if you're done setting database in .env
```bash
php artisan migrate
php artisan db:seed
```


## To run the application
```bash
php artisan serve
```


## Account Demo SuperAdmin
1.  Email: **superadmin@gmail.com**
    Password: **12345678**

## Account Demo admin
1.  Email: **admin@gmail.com**
    Password: **12345678**

## Account Demo kurir
1.  Email: **kurir@gmail.com**
    Password: **12345678**

## Account Demo finance
1.  Email: **finance@gmail.com**
    Password: **12345678**
