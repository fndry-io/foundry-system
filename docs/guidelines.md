## Development Guideline and Rules

The following guideline and rules are designed to help build well structured and easy to following modules. 

### Process

When building a new module, it's best to follow the development process as follows, focusing on the minimal set of features first in order to get the module built out.

**Backend:**

1. Create the base module, ensuring the composer and generic package is in place.
1. Map out the tables and the migration files.
1. Create any base Pick Lists classes.
1. Create Models, Repositories, Module Seeder and Faker classes.
1. Run the Seeder and ensure the migrations work correctly (ensure your composer.json file has the classmap correctly set to allow composer to pick up the seed classes).
1. Create the Services, Input Types and Input classes.
1. Create the Controllers and Requests and SyncPermissions.
1. Build unit tests for all the HTTP requests and ensure the unit tests fully test the expected data was created or actioned in the database.

**Frontend Packages:**

1. Ensure the base package is created and has the correct details
1. Migrate from fresh and seed the database with the modules seeder
1. Test the log in is working as expected
1. Build out your components, widgets, and applications

### Rules

1. The main Seed should fully seed the application, calling the FoundrySeeder as well. This will allow the module to be built and tested on it's own without running any other seeds or requirements. 

### Seeding and Unit Testing

All modules should provide a base "Main" Seeder class which can be used to seed the entire application with everything it needs in order to be tested fully. This allows the Module to be developed on independently from any other modules or data.

This way, to start or continue development on a Module, run would run the following commands:

1. ```php artisan migrate:fresh```
1. ```php artisan db:seed --class=YourModuleSeeder```

### Version Control Standards

Whilst it is not required, all modules should follow the git-flow approach to version control.