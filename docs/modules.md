# Modules
Modules provide a simple approach to extending and enhancing the application. Each Module is in effect a mini Laravel Application and Package, and is installed the same way any other Laravel Package would be.

Modules provide routes, Controllers, Input Types and Input Collections, Services, Repositories, and Models. They can also provide Middleware and any other Laravel feature.

## Installation and Config
All modules are installed into the root modules folder with a folder structure of ```/[modules directory]/author/name```. The author being the company or person that created the module, and the name being the actual name of the module.

### File Structure
Modules are structured the same was that a Laravel Application is. An example of that structure is:

- /app
  - /Console
  - /Http
    - /Controllers
    - /Responses
    - /Requests
  - /Events
  - /Inputs (Foundry Input Types and Input Collections)
  - /Listeners
  - /Models
  - /Providers
    - ModuleProvider.php
  - /Repositories
  - /Services
- /config
- /database
  - /factories
  - /migrations
  - /seeds
- /routes
- /resources
  - /packages (Foundry javascript packages)
  - /views
  - /lang
- /tests
- composer.json

### Composer & Dependencies
Every Module must have a ```composer.json``` file, specify it's dependencies and how the module should be loaded (autoload: psr-4, files, classmap).

An extra key of "type" is also required and must be equal to ```foundry-module```. This is the secret sauce that ensures that when you require a module into any project via's it's composer file, the module will be installed into that projects "modules" directory based on the module name which must also provide ```[author-name]/[module-name]```.

### Migrations
As in any application, tables should be created. So each module should provide it's own tables for migrating the module and ensuring it has all its tables in place.

When adding migrations to your module, remember to import your migrations table in your ModuleProvider class so that Laravel knows about them.

## Packages
Packages for a module should typically be VueJS plugins and follow the same principles. 

Packages for a module should be saved in that modules ```resources/packages/[package-name]``` directory.

Whilst the structure of the package can be anything, it is generally good practice to use the default structure as below. This helps to maintain consistency with npm packages as a whole and makes it easier for future adaptations or changes in the Framework itself.

### File Structure
Packages should be typically structured as:

- /src
  - /apps
    - index.js
  - /components
  - /mixins
  - /styles
  - /types
  - /widgets
  - index.js
- /demo

```/src/apps/index.js``` should return an array of Application names and components. E.G.

```$javascript
import ProjectsApp from 'ProductsApp';
...

export default const [
    {
        name: 'products',
        component: ProjectsApp
    },
    ...
]
```
Then ```src/index.js``` must export the VueJS plugin as well as the "Apps" array above from index.js, and then any other widgets, components, and mixins it wishes to expose. 

### Npm and Dependencies
All packages should have a package.json file and can also contain the package-lock.json file.

The package.json file should contain the list of dependencies the package requires, and these will be interpreted just like any node_module dependencies are.

_Hint:_ A package is actually a normal npm package. The ony difference is we import them differently in the application using ```npm install file:../../path/to/package```.

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
