# Foundry Users Plugin

Foundry Users is a plugin for the Foundry Framework to assist with common User based functionality.

## Installation

The process to install this module is as follows:

- Run the migrations if not already done `php artisan module:migrate FoundryUsers`
- Run `php artisan passport:install` after the migration has been performed.
- Generate encryption keys `php artisan passport:keys`.
- Make the Admin user with `php artisan foundry-users:make-super-user [first name] [last name] [email]`.
- Seed the database with the FoundryUsersDatabaseSeeder `php artisan module:seed FoundryUsers`
